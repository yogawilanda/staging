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
	if (isCallActive) {
		isCallActive = false;
		if (pollInterval) clearInterval(pollInterval);
		if (peerConnection) peerConnection.close();

		document.getElementById('actionText').innerText = "FIND NEW CALL";
		document.getElementById('actionBtn').className = "px-6 py-3 border border-emerald-500 bg-emerald-950/40 text-emerald-400 hover:bg-emerald-500 hover:text-black text-xs font-bold tracking-widest uppercase transition-colors flex items-center gap-2 font-mono";

		document.getElementById('statusPing').className = "w-3 h-3 bg-red-600 rounded-full";
		document.getElementById('statusTitle').innerText = "VOID//DISCONNECTED";
		document.getElementById('sessionText').innerText = "TRANSMISSION ENDED";
		document.getElementById('footerStatus').innerText = "STATUS: IDLE";

		resetPeerUI();
		appendLog('<span class="text-red-500/80">&gt; Transmission terminated by user.</span>');
	} else {
		window.location.reload();
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
	if (isMatchedUI) return;
	isMatchedUI = true;

	document.getElementById('statusPing').className = "w-3 h-3 bg-emerald-500 animate-ping rounded-full";
	document.getElementById('statusTitle').innerText = "VOID//CONNECTED";
	document.getElementById('sessionText').innerText = "ACTIVE PEER CONNECTION (2/2)";
	document.getElementById('footerStatus').innerText = "STATUS: BROADCASTING";

	const strangerCard = document.getElementById('strangerCard');
	if (strangerCard) {
		strangerCard.className = "border border-zinc-800 bg-zinc-950 p-6 flex flex-col items-center justify-center gap-3 relative speaking transition-all";
	}

	const peerAvatar = document.getElementById('peer-avatar');
	if (peerAvatar) {
		peerAvatar.innerHTML = `<span class="font-pixel text-3xl text-white">PEER</span><span class="absolute -bottom-1 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-black"></span>`;
		peerAvatar.className = "w-20 h-20 rounded-full bg-zinc-900 border border-emerald-500/50 flex items-center justify-center relative";
	}

	const peerCallsign = document.getElementById('peer-callsign');
	if (peerCallsign) {
		peerCallsign.className = "text-sm font-bold text-white tracking-wider font-mono";
		peerCallsign.textContent = peer.callsign || 'Anon_Peer';
	}

	const peerCountry = document.getElementById('peer-country');
	if (peerCountry) {
		peerCountry.innerHTML = `NATION: <span class="text-white font-bold">${peer.country_code || 'UNKNOWN'}</span>`;
	}

	appendLog(`<div class="text-emerald-400">&gt; Connected with peer [${peer.callsign || 'Anon'}]. Audio channel open.</div>`);
}

function resetPeerUI() {
	const strangerCard = document.getElementById('strangerCard');
	if (strangerCard) {
		strangerCard.className = "border border-dashed border-zinc-900 bg-black/40 p-6 flex flex-col items-center justify-center gap-3 relative text-zinc-700";
		strangerCard.innerHTML = `
            <div class="w-20 h-20 rounded-full border border-zinc-900 flex items-center justify-center font-pixel text-3xl">?</div>
            <div class="text-center">
                <div class="text-xs font-mono tracking-widest text-zinc-600">[PEER DISCONNECTED]</div>
                <div class="text-[10px] text-zinc-700 font-mono mt-1">NATION: NONE</div>
            </div>`;
	}
}