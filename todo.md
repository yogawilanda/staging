# 🚀 VOID//CALLS - Technical Roadmap & Progress Tracker

> **Stack Focus:** Laravel, Tailwind CSS, Native WebRTC/STUN, MySQL (Memory/InnoDB Hybrid).

---

## 🛠️ Phase 1: Core Setup & Frontend Layouts

- [x] **Setup Basic UI Landing Page** (`welcome.blade.php`)
    - [x] Style CRT/Retro terminal (VT323/Share Tech Mono font, scanlines).
    - [x] Form Alias/Callsign & Frequency Selection.
    - [x] Routing Laravel `room.join`.
- [x] **Setup Basic UI Room Active Call** (`room.blade.php`)
    - [x] Grid 1-on-1 direct peer call layout.
    - [x] Dynamic Action Button (`END CALL` -> `FIND NEW CALL`).
    - [x] Terminal Frequency Log & Quick Chat Box.
    - [x] Display Country / Nation text.
- [ ] **Setup Middle Menu Views (Static/Modals)**
    - [ ] Page / Modal: `[ABOUT_US]`
    - [ ] Page / Modal: `[REPORTS]` (Form lapor user)
    - [ ] Page / Modal: `[CONTACT_US]`
    - [ ] Page / Modal: `[SECURITY]`

---

## 🗄️ Phase 2: Database & Backend Architecture (No-Redis / MySQL Hybrid)

- [x] **Migration & Schema Setup**
    - [x] Setup Tabel `active_matchmaking` (Engine: `MEMORY` / RAM) buat nampung user _waiting/in_call_ & heartbeat.
    - [x] Setup Tabel `calls` (Engine: `InnoDB`) buat nyatet transaksi durasi call & statistik.
    - [x] Setup Tabel `reports` (Engine: `InnoDB`) buat penampungan laporan pelanggaran.
- [x] **Matchmaking Engine (Laravel Controller / Services)**
    - [x] Endpoint /api/ping: Simpan/update status heartbeat user aktif via AJAX/Fetch.
    - [x] Endpoint /api/matchmake: Logic cari lawan bicara yang statusnya waiting.
    - [x] Endpoint /api/leave: Handle user klik Disconnect / close tab.
    - [x] Endpoint /api/signal/send & /get: Pertukaran SDP Offer/Answer & ICE Candidates.
    - [x] Endpoint /api/signal/cleanup: Auto-bersihkan sesi dan state lawan saat refresh atau putus.

- [ ] **Cron Job & Housekeeping**
    - [ ] Scheduler Laravel (`php artisan schedule:run`) buat auto-purge user _ghosting_ yang pings-nya mati > 15 detik.

- [ ] \*\*Call History & Analytics Logger (Portofolio Tracking)
    - [ ] Simpan rekam jejak durasi dan statistik call ke tabel calls sebelum sesi antrean dibersihkan/di-purge.

---

[x] STUN Server Configuration

[x] Integration Public STUN Servers (Google STUN: stun:stun.l.google.com:19302).

[x] Signaling Controller / Polling Mechanism

[x] Exchange SDP Offer & Answer antar 2 user terikat (via HTTP Long-Polling / SSE / Fetch API ringan).

[x] Exchange ICE Candidates.

[x] Audio Stream Integration (JavaScript Client)

[x] Request Izin Microphone (navigator.mediaDevices.getUserMedia).

[x] Attach Remote Stream ke <audio> element (Hidden audio player).

[x] Mute / Unmute Local Microphone Logic.

---

## 🛡️ Phase 4: Security, Moderation & Anti-Abuse

- [ ] **Session & Rate Limiting**
    - [ ] Throttle request match (Cegah spammer klik _Find New Call_ bertubi-tubi).
    - [ ] IP Hash generation (Anonymized IP) untuk identifikasi _banned users_ tanpa simpan IP mentah.
- [ ] **Reporting Engine**
    - [ ] Integrasi tombol Report di UI ke tabel `reports`.
    - [ ] Auto-block temporary jika IP Hash yang sama dapat >= 3 report dalam kurun waktu singkat.

---

## 💰 Phase 5: Monetization & Growth Features

- [ ] **Country/Nation Selection Filter (MVP Micro-payment)**
    - [ ] Lock pilihan negara jika status user = gratisan.
    - [ ] Integrate Webhook QRIS / Payment Gateway lokal (misal: Tripay / Midtrans / Xendit).
    - [ ] Store VIP Pass Token di `localStorage` browser.
- [ ] **AdSense / Native Text Banner Integration**
    - [ ] Slot iklan teks gaya terminal di sidebar `FREQUENCY LOG`.

---

## 🌐 Phase 6: Deployment & Launch

- [ ] **Production Hosting Config (Business Web Hosting)**
    - [ ] Setup SSL / HTTPS (Wajib untuk akses WebRTC Microphone).
    - [ ] Cron Job Setup di cPanel/DirectAdmin.
- [ ] **Initial Seeding & Social Growth**
    - [ ] Metadata Open Graph (SEO/Social Preview) pas link di-share di Twitter/Discord.
    - [ ] Soft launch testing (Seeding user manual di jam-jam sibuk).
