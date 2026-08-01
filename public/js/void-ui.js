// Void UI Controller
function toggleMute() {
	if (!localStream) return;
	isMuted = !isMuted;
	localStream.getAudioTracks().forEach(track => track.enabled = !isMuted);

	const btnText = document.getElementById('muteText');
	const btnIcon = document.getElementById('muteIcon');
	const btn = document.getElementById('muteBtn');

	if (isMuted) {
		btnText.innerText = "UNMUTE MIC";
		btnIcon.innerText = "🔇";
		btn.classList.add('border-red-600', 'text-red-400');
		btn.classList.remove('text-white');
	} else {
		btnText.innerText = "MUTE MIC";
		btnIcon.innerText = "🎙️";
		btn.classList.remove('border-red-600', 'text-red-400');
		btn.classList.add('text-white');
	}
}

function handleCallAction() {
	const actionText = document.getElementById('actionText').innerText;

	if (actionText === "END CALL") {
		// Tahap 1: Putus panggilan (End Call diklik)
		isCallActive = false;
		isMatched = false;
		isMatchedUI = false;

		if (pollInterval) { console.log('[UI] handleCallAction: clearing pollInterval'); clearInterval(pollInterval); pollInterval = null; }
		if (peerConnection) { console.log('[UI] handleCallAction: closing peerConnection'); peerConnection.close(); peerConnection = null; }

		// Kirim sinyal leave ke server
		fetch('/api/v1/leave', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
			body: JSON.stringify({ session_token: sessionToken })
		}).catch(err => console.error(err));

		// Ubah tombol jadi "FIND NEW CALL"
		const btnTextElem = document.getElementById('actionText');
		const actionBtn = document.getElementById('actionBtn');
		if (btnTextElem) btnTextElem.innerText = "FIND NEW CALL";
		if (actionBtn) {
			actionBtn.className = "px-6 py-3 border border-emerald-500 bg-emerald-950/40 text-emerald-400 hover:bg-emerald-500 hover:text-black text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2 font-mono";
		}

		const statusPing = document.getElementById('statusPing');
		if (statusPing) statusPing.className = "w-3 h-3 bg-zinc-600 rounded-full";

		document.getElementById('statusTitle').innerText = "VOID//IDLE";
		document.getElementById('sessionText').innerText = "TRANSMISSION ENDED";
		document.getElementById('footerStatus').innerText = "STATUS: IDLE";

		resetPeerUI();
		appendLog('<span class="text-zinc-500">&gt; Call ended. Click "FIND NEW CALL" to scan frequencies.</span>');

	} else if (actionText === "FIND NEW CALL" || actionText === "FINDING...") {
		// Tahap 2: "FIND NEW CALL" diklik, ubah jadi "FINDING..." lalu aktifkan kembali proses pencarian
		const btnTextElem = document.getElementById('actionText');
		const actionBtn = document.getElementById('actionBtn');

		if (btnTextElem) btnTextElem.innerText = "FINDING...";
		if (actionBtn) {
			actionBtn.className = "px-6 py-3 border border-zinc-700 bg-zinc-900 text-zinc-400 text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2 font-mono";
		}

		// Reset state untuk mulai mencari lagi
		isCallActive = true;
		isMatched = false;
		isMatchedUI = false;

		document.getElementById('statusTitle').innerText = "VOID//SEARCHING";
		document.getElementById('sessionText').innerText = "SEARCHING FOR PEER...";
		document.getElementById('footerStatus').innerText = "STATUS: MATCHMAKING";

		resetPeerUI();
		appendLog('<span class="text-zinc-500">&gt; Scanning active frequencies for new peer...</span>');

		// Restart interval matchmaking jika sebelumnya berhenti
		handleFindNewCall();
	}
}

function resetPeerUI() {
	isMatchedUI = false;
	const strangerCard = document.getElementById('strangerCard');
	if (strangerCard) {
		strangerCard.className = "border border-dashed border-zinc-800 bg-zinc-950/50 p-6 flex flex-col items-center justify-center gap-3 relative transition-all";
		strangerCard.innerHTML = `
            <div id="peer-avatar" class="w-20 h-20 rounded-full bg-zinc-900 border border-zinc-700 flex items-center justify-center relative font-pixel text-3xl text-zinc-500">
                ?
            </div>
            <div class="text-center">
                <div id="peer-callsign" class="text-sm font-bold text-zinc-600 tracking-wider font-mono">
                    STANDBY
                </div>
                <div id="peer-country" class="text-xs text-zinc-700 font-mono mt-1">
                    NATION: NONE
                </div>
            </div>`;
	}
}

function sendMessage(e) {
	e.preventDefault();
	const input = document.getElementById('chatInput');
	if (input.value.trim() !== '') {
		appendLog(`<div><span class="text-white font-bold">YOU:</span> <span class="text-zinc-300">${input.value}</span></div>`);
		input.value = '';
	}
}

function appendLog(htmlContent) {
	const chatLog = document.getElementById('chatLog');
	if (chatLog) {
		chatLog.innerHTML += htmlContent;
		chatLog.scrollTop = chatLog.scrollHeight;
	}
}

function updateMatchedUI(peer) {
	if (!peer) return;
	if (isMatchedUI) return;
	isMatchedUI = true;

	// Target Navbar & Page elements
	const statusPing = document.getElementById('statusPing');
	if (statusPing) statusPing.className = "w-3 h-3 bg-emerald-500 animate-ping rounded-full";

	const statusTitle = document.getElementById('statusTitle');
	if (statusTitle) statusTitle.innerText = "VOID//CONNECTED";

	const sessionText = document.getElementById('sessionText');
	if (sessionText) sessionText.innerText = "ACTIVE PEER CONNECTION (2/2)";

	const footerStatus = document.getElementById('footerStatus');
	if (footerStatus) footerStatus.innerText = "STATUS: BROADCASTING";

	// Ubah tombol jadi "END CALL" saat berhasil terhubung (match)
	const actionText = document.getElementById('actionText');
	const actionBtn = document.getElementById('actionBtn');
	if (actionText) actionText.innerText = "END CALL";
	if (actionBtn) {
		actionBtn.className = "px-6 py-3 border border-red-900/80 bg-red-950/40 text-red-400 hover:bg-red-900 hover:text-white text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2 font-mono";
	}

	const strangerCard = document.getElementById('strangerCard');
	if (strangerCard) {
		strangerCard.className = "border border-zinc-800 bg-zinc-950 p-6 flex flex-col items-center justify-center gap-3 relative speaking transition-all";
	}

	const peerAvatar = document.getElementById('peer-avatar');
	if (peerAvatar) {
		peerAvatar.innerHTML = `<span class="font-pixel text-3xl text-white">PEER</span><span class="absolute -bottom-1 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-black"></span>`;
		peerAvatar.className = "w-20 h-20 rounded-full bg-zinc-900 border border-emerald-500/50 flex items-center justify-center relative";
	}

	// Ambil data callsign & country dari objek peer API
	const pCallsign = peer.callsign || peer.name || 'ANON_OPERATOR';
	const pCountry = peer.country_code || peer.country || 'ID';

	const peerCallsign = document.getElementById('peer-callsign');
	if (peerCallsign) {
		peerCallsign.className = "text-sm font-bold text-white tracking-wider font-mono";
		peerCallsign.textContent = pCallsign;
	}

	const peerCountry = document.getElementById('peer-country');
	if (peerCountry) {
		peerCountry.className = "text-xs text-emerald-400 font-mono mt-1";
		peerCountry.innerHTML = `NATION: <span class="text-white font-bold">${pCountry}</span>`;
	}

	appendLog(`<div class="text-emerald-400">&gt; Connected with peer [${pCallsign}]. Audio channel open.</div>`);
}

// Contoh urutan di JS ketika tombol Find New Call dipencet
async function handleFindNewCall() {

	try {
		// 1. Daftarkan ulang session ke database lewat ping
		const userCallsign = typeof callsign !== 'undefined' ? callsign : (document.getElementById('callsign')?.value || 'ANON');
		console.log('[UI] handleFindNewCall: pinging with', { sessionToken, userCallsign });

		if (typeof window.sendPing === 'function') {
			await window.sendPing({ sessionToken: sessionToken, callsign: userCallsign, countryCode: 'ID', visitor: false });
			console.log('[UI] handleFindNewCall: ping completed (sendPing)');
		} else {
			await fetch('/api/v1/ping', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
				body: JSON.stringify({
					session_token: sessionToken,
					callsign: userCallsign,
					country_code: 'ID'
				})
			});
			console.log('[UI] handleFindNewCall: ping completed (fetch)');
		}
	} catch (err) {
		console.error("Ping error on re-search:", err);
	}

	// 2. Restart interval matchmaking
	if (!pollInterval) {
		console.log('[UI] handleFindNewCall: starting heartbeat/matchmaking (pollInterval was falsy)');
		startHeartbeatAndMatchmaking();
	} else {
		console.log('[UI] handleFindNewCall: pollInterval already set, skipping start (value):', pollInterval);
	}
}