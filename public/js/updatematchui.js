function updateMatchedUI(peer) {
	if (isMatchedUI) return;
	isMatchedUI = true;

	document.getElementById('statusPing').className = "w-3 h-3 bg-emerald-500 animate-ping rounded-full";
	document.getElementById('statusTitle').innerText = "VOID//CONNECTED";
	document.getElementById('sessionText').innerText = "ACTIVE PEER CONNECTION (2/2)";
	document.getElementById('footerStatus').innerText = "STATUS: BROADCASTING";

	const strangerCard = document.getElementById('strangerCard');
	strangerCard.className = "border border-zinc-800 bg-zinc-950 p-6 flex flex-col items-center justify-center gap-3 relative speaking transition-all";

	document.getElementById('peer-avatar').innerHTML = `<span class="font-pixel text-3xl text-white">PEER</span><span class="absolute -bottom-1 right-0 w-4 h-4 bg-emerald-500 rounded-full border-2 border-black"></span>`;
	document.getElementById('peer-avatar').className = "w-20 h-20 rounded-full bg-zinc-900 border border-emerald-500/50 flex items-center justify-center relative";

	document.getElementById('peer-callsign').className = "text-sm font-bold text-white tracking-wider font-mono";
	document.getElementById('peer-callsign').textContent = peer.callsign || 'Anon_Peer';

	document.getElementById('peer-country').innerHTML = `NATION: <span class="text-white font-bold">${peer.country_code || 'UNKNOWN'}</span>`;

	appendLog(`<div class="text-emerald-400">&gt; Connected with peer [${peer.callsign}]. Audio channel open.</div>`);
}
