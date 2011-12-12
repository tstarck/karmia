/* json.js */

function tee_solu() {
	if (arguments.length <= 1) console.log("arg.len <= 1");

	var solu = document.createElement('td');
	solu.className = arguments[0];

	for (var i=1; i<arguments.length; i++) {
		var t = document.createTextNode(arguments[i]);
		solu.appendChild(t);
	}

	return solu;
}

function tee_rivi(nimi, laji, latin, alkupera, vari) {
	var rivi = document.createElement('tr');

	rivi.appendChild(tee_solu('nimi', nimi));
	rivi.appendChild(tee_solu('laji', laji, ' / ', latin));
	rivi.appendChild(tee_solu('alkup', alkupera));
	rivi.appendChild(tee_solu('vari', vari));

	return rivi;
}

function kaarmeile(data) {
	var taulu = document.createElement('table');

	data.forEach(function(e, i, a) {
		taulu.appendChild(tee_rivi(e.nimi, e.laji, e.latin, e.alkupera, e.vari));
	});

	document.body.appendChild(taulu);
}

function handlaa(data) {
	window.addEventListener('load', function() {
		kaarmeile(data);
	});
}
