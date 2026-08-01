<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActiveMatchmaking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MatchmakingController extends Controller
{
    /**
     * Endpoint /api/v1/ping
     */
    public function ping(Request $request)
    {
        $validated = $request->validate([
            'session_token' => 'required|string|max:64',
            'callsign'      => 'required|string|max:50',
            'country_code'  => 'nullable|string|max:10',
            'visitor'       => 'nullable|boolean',
        ]);

        $isVisitor = isset($validated['visitor']) && $validated['visitor'];

        $ipHash = hash('sha256', $request->ip());

        $user = ActiveMatchmaking::where('session_token', $validated['session_token'])->first();

        if (!$user) {
            $user = ActiveMatchmaking::create([
                'id'            => (string) Str::uuid(),
                'session_token' => $validated['session_token'],
                'callsign'      => $validated['callsign'],
                'country_code'  => $validated['country_code'] ?? 'ID',
                'ip_hash'       => $ipHash,
                // mark visitors differently so they aren't eligible for matchmaking
                'status'        => $isVisitor ? 'visitor' : 'waiting',
                'last_ping_at'  => now(),
            ]);
        } else {
            $user->update([
                'callsign'     => $validated['callsign'],
                'country_code' => $validated['country_code'] ?? 'ID',
                'ip_hash'      => $ipHash,
                'last_ping_at' => now(),
                // if a visitor becomes an active caller, promote status to waiting
                'status'       => $isVisitor ? ($user->status ?? 'visitor') : 'waiting',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user_id' => $user->id,
                'status'  => $user->status,
            ],
        ]);
    }

    /**
     * Endpoint /api/v1/matchmake
     */
    public function matchmake(Request $request)
    {
        $mySessionToken = $request->input('session_token');

        if (!$mySessionToken) {
            return response()->json(['status' => 'searching']);
        }

        // 1. Ambil data session saya
        $mySession = ActiveMatchmaking::where('session_token', $mySessionToken)->first();

        if (!$mySession) {
            return response()->json(['status' => 'searching']);
        }

        // 2. Jika saya sudah di-match / paired oleh user lain
        $peerSessionToken = $mySession->paired_with ?? $mySession->matched_with ?? $mySession->peer_session ?? null;

        if ($peerSessionToken) {
            $peer = ActiveMatchmaking::where('session_token', $peerSessionToken)
                ->orWhere('id', $peerSessionToken)
                ->first();

            if ($peer) {
                // Tentukan role secara dinamis berdasarkan perbandingan token (alfanumerik) agar konsisten tanpa kolom database
                $role = ($mySessionToken < $peer->session_token) ? 'initiator' : 'receiver';

                return response()->json([
                    'status' => 'matched',
                    'role'   => $role,
                    'peer'   => [
                        'id'           => $peer->session_token,
                        'callsign'     => $peer->callsign ?? 'Anon_Peer',
                        'country_code' => $peer->country_code ?? 'ID',
                    ]
                ]);
            } else {
                // KUNCI UTAMA: Jika partner-nya sudah lenyap dari DB, reset status kita jadi searching/disconnected!
                $mySession->update([
                    'status'      => 'waiting',
                    'paired_with' => null,
                ]);

                return response()->json([
                    'status' => 'disconnected',
                    'message' => 'Peer has disconnected.'
                ]);
            }
        }

        // 3. Jika belum match, cari user lain yang WAITING & aktif ping 10 detik terakhir
        $peer = ActiveMatchmaking::where('session_token', '!=', $mySessionToken)
            ->where('status', 'waiting')
            ->whereNull('paired_with')
            ->where('last_ping_at', '>=', now()->subSeconds(10))
            ->first();

        if ($peer) {
            // Update status dua-duanya jadi 'matched'
            $mySession->update([
                'status'      => 'matched',
                'paired_with' => $peer->session_token,
            ]);

            $peer->update([
                'status'      => 'matched',
                'paired_with' => $mySessionToken,
            ]);

            $role = ($mySessionToken < $peer->session_token) ? 'initiator' : 'receiver';

            return response()->json([
                'status' => 'matched',
                'role'   => $role,
                'peer'   => [
                    'id'           => $peer->session_token,
                    'callsign'     => $peer->callsign ?? 'Anon_Peer',
                    'country_code' => $peer->country_code ?? 'ID',
                ]
            ]);
        }

        return response()->json(['status' => 'searching']);
    }

    /**
     * Endpoint /api/v1/active_counts
     * Returns JSON counts for active users, visitors, and callers (recent pings window)
     */
    public function activeCounts(Request $request)
    {
        $windowSeconds = 15;

        $total = ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds($windowSeconds))->count();

        $visitors = ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds($windowSeconds))
            ->where('status', 'visitor')
            ->count();

        $callers = ActiveMatchmaking::where('last_ping_at', '>=', now()->subSeconds($windowSeconds))
            ->whereIn('status', ['waiting','matched'])
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total' => $total,
                'visitors' => $visitors,
                'callers' => $callers,
            ],
        ]);
    }

    /**
     * Endpoint /api/v1/leave
     */
    public function leave(Request $request)
    {
        $validated = $request->validate([
            'session_token' => 'required|string|max:64',
        ]);

        $currentUser = ActiveMatchmaking::where('session_token', $validated['session_token'])->first();

        if ($currentUser) {
            if ($currentUser->paired_with) {
                ActiveMatchmaking::where('session_token', $currentUser->paired_with)
                    ->orWhere('id', $currentUser->paired_with)
                    ->update([
                        'status'      => 'waiting',
                        'paired_with' => null,
                    ]);
            }

            $currentUser->delete();
        }

        return response()->json(['status' => 'success', 'message' => 'User disconnected successfully.']);
    }

    public function sendSignal(Request $request)
    {
        DB::table('signals')->insert([
            'sender_session'   => $request->sender_session,
            'receiver_session' => $request->receiver_session,
            'type'             => $request->type,
            'payload'          => is_array($request->payload) ? json_encode($request->payload) : $request->payload,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function getSignals(Request $request)
    {
        $sessionToken = $request->input('session_token');

        $signals = DB::table('signals')
            ->where('receiver_session', $sessionToken)
            ->get();

        if ($signals->isNotEmpty()) {
            DB::table('signals')
                ->where('receiver_session', $sessionToken)
                ->delete();
        }

        return response()->json([
            'signals' => $signals
        ]);
    }

    public function cleanup(Request $request)
    {
        $token = $request->input('session_token');

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Token required'], 400);
        }

        $currentUser = DB::table('active_matchmaking')->where('session_token', $token)->first();

        if ($currentUser && !empty($currentUser->paired_with)) {
            DB::table('active_matchmaking')
                ->where('session_token', $currentUser->paired_with)
                ->orWhere('id', $currentUser->paired_with)
                ->update([
                    'status' => 'waiting',
                    'paired_with' => null,
                ]);
        }

        DB::table('signals')
            ->where('sender_session', $token)
            ->orWhere('receiver_session', $token)
            ->delete();

        DB::table('active_matchmaking')
            ->where('session_token', $token)
            ->orWhere('paired_with', $token)
            ->delete();

        return response()->json(['status' => 'cleaned']);
    }
}
