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
  - [x] Setup Tabel `active_matchmaking` (Engine: `MEMORY` / RAM) buat nampung user *waiting/in_call* & heartbeat.
  - [x] Setup Tabel `calls` (Engine: `InnoDB`) buat nyatet transaksi durasi call & statistik.
  - [x] Setup Tabel `reports` (Engine: `InnoDB`) buat penampungan laporan pelanggaran.
- [ ] **Matchmaking Engine (Laravel Controller / Services)**
  - [ ] Endpoint `/api/ping`: Simpan/update status heartbeat user aktif via AJAX/Fetch.
  - [ ] Endpoint `/api/matchmake`: Logic cari lawan bicara yang statusnya `waiting`.
  - [ ] Endpoint `/api/leave`: Handle user klik Disconnect / close tab (update status ke `idle`/hapus dari antrean).
- [ ] **Cron Job & Housekeeping**
  - [ ] Scheduler Laravel (`php artisan schedule:run`) buat auto-purge user *ghosting* yang pings-nya mati > 15 detik.

---

## 🎙️ Phase 3: WebRTC & Signaling Setup
- [ ] **STUN Server Configuration**
  - [ ] Integration Public STUN Servers (Google STUN: `stun:stun.l.google.com:19302`).
- [ ] **Signaling Controller / Polling Mechanism**
  - [ ] Exchange SDP Offer & Answer antar 2 user terikat (via HTTP Long-Polling / SSE / Fetch API ringan).
  - [ ] Exchange ICE Candidates.
- [ ] **Audio Stream Integration (JavaScript Client)**
  - [ ] Request Izin Microphone (`navigator.mediaDevices.getUserMedia`).
  - [ ] Attach Remote Stream ke `<audio>` element (Hidden audio player).
  - [ ] Mute / Unmute Local Microphone Logic.

---

## 🛡️ Phase 4: Security, Moderation & Anti-Abuse
- [ ] **Session & Rate Limiting**
  - [ ] Throttle request match (Cegah spammer klik *Find New Call* bertubi-tubi).
  - [ ] IP Hash generation (Anonymized IP) untuk identifikasi *banned users* tanpa simpan IP mentah.
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