

// Void WebRTC Engine
function createPeerConnection(targetPeerId) {
	peerConnection = new RTCPeerConnection(rtcConfig);

	// Ambil track dari localStream
	if (localStream) {
		localStream.getTracks().forEach(track => {
			peerConnection.addTrack(track, localStream);
		});
	} else {
		console.error('[VOID//WEBRTC] Local stream belum ada saat buat PeerConnection!');
	}

	// Tangkap stream lawan
	peerConnection.ontrack = (event) => {
		console.log('[VOID//WEBRTC] ONTRACK TRIGGERED!', event.streams);
		const remoteAudio = document.getElementById('remote-audio');
		if (remoteAudio && event.streams[0]) {
			remoteAudio.srcObject = event.streams[0];
			remoteAudio.play().catch(e => console.error("Autoplay blocked:", e));
		}
	};

	peerConnection.onicecandidate = (event) => {
		if (event.candidate) {
			sendSignal(targetPeerId, 'ice-candidate', event.candidate);
		}
	};
}

async function initiateCall(targetPeerId) {
	console.log('[VOID//WEBRTC] Initiating Call to Peer:', targetPeerId);
	createPeerConnection(targetPeerId);

	const offer = await peerConnection.createOffer();
	await peerConnection.setLocalDescription(offer);

	sendSignal(targetPeerId, 'offer', offer);
}

// Helper khusus untuk membersihkan SDP sebelum masuk WebRTC
function parseSDPPayload(rawPayload) {
	let payload = rawPayload;
	if (typeof payload === 'string') {
		try { payload = JSON.parse(payload); } catch (e) { return null; }
	}
	if (typeof payload === 'string') {
		try { payload = JSON.parse(payload); } catch (e) { return null; }
	}

	if (!payload || !payload.sdp) return null;

	// Bersihkan baris msid / ssrc yang ketempelan string acak
	let cleanedSdp = payload.sdp.split('\n').map(line => {
		if (line.startsWith('a=msid:') || line.startsWith('a=ssrc:')) {
			const parts = line.trim().split(' ');
			if (parts.length > 2) {
				// Ambil cuma 2 bagian pertama (atribut dan ID utamanya)
				return `${parts[0]} ${parts[1]}`;
			}
		}
		return line.trim();
	}).join('\r\n');

	return new RTCSessionDescription({
		type: payload.type,
		sdp: cleanedSdp + '\r\n'
	});
}

async function checkPendingSignals() {
	try {
		const res = await fetch('/api/v1/signal/get', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
			body: JSON.stringify({ session_token: sessionToken })
		});
		const data = await res.json();

		if (data.signals && data.signals.length > 0) {
			for (const signal of data.signals) {

				if (signal.type === 'offer') {
					const sessionDesc = parseSDPPayload(signal.payload);
					if (!sessionDesc) continue;

					currentPeerSession = signal.sender_session;
					createPeerConnection(signal.sender_session);

					await peerConnection.setRemoteDescription(sessionDesc);

					const answer = await peerConnection.createAnswer();
					await peerConnection.setLocalDescription(answer);
					sendSignal(signal.sender_session, 'answer', answer);

					while (pendingCandidates.length) {
						const cand = pendingCandidates.shift();
						await peerConnection.addIceCandidate(cand);
					}
				} else if (signal.type === 'answer' && peerConnection) {
					const sessionDesc = parseSDPPayload(signal.payload);
					if (!sessionDesc) continue;

					await peerConnection.setRemoteDescription(sessionDesc);

					while (pendingCandidates.length) {
						const cand = pendingCandidates.shift();
						await peerConnection.addIceCandidate(cand);
					}
				} else if (signal.type === 'ice-candidate') {
					let candPayload = signal.payload;
					if (typeof candPayload === 'string') {
						try { candPayload = JSON.parse(candPayload); } catch (e) { }
					}

					// Pastikan ICE Candidate ditambahkan jika remoteDescription sudah terpasang
					if (peerConnection && peerConnection.remoteDescription) {
						await peerConnection.addIceCandidate(new RTCIceCandidate(candPayload));
					} else {
						// Simpan sementara jika remoteDescription belum siap
						pendingCandidates.push(new RTCIceCandidate(candPayload));
					}
				}
			}
		}
	} catch (e) {
		console.error('[VOID//SIGNALING_ERROR]', e);
	}
}

async function sendSignal(receiverSession, type, payload) {
	await fetch('/api/v1/signal/send', {
		method: 'POST',
		headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
		body: JSON.stringify({
			sender_session: sessionToken,
			receiver_session: receiverSession,
			type: type,
			payload: payload
		})
	});
}

async function cleanupOldSession() {
    try {
        await fetch('/api/v1/signal/cleanup', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ session_token: sessionToken })
        });
        console.log('[VOID//CLEANUP] Sesi lama berhasil dibersihkan dari DB.');
    } catch (e) {
        console.error('[VOID//CLEANUP_ERROR]', e);
    }
}