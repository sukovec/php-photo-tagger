document.asociatedKeys = {};

function asociateKeyPresses(checkbox, key) {
	var itms = document.querySelectorAll('[data-asckey]');
	for (var i = 0; i < itms.length; i++) {
		if (!itms.hasOwnProperty(i)) continue; 

		document.asociatedKeys[itms[i].getAttribute("data-asckey")] = itms[i];
	}

	document.addEventListener("keydown", onBodyKeyPress);
}

function onBodyKeyPress(evt) {
	if (document.asociatedKeys.hasOwnProperty(evt.key)) {
		var cb = document.asociatedKeys[evt.key];

		cb.checked = !cb.checked;
	}
}
