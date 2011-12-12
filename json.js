/* json.js */

function yksityiskohdat() {
}

function tee_solu() {
	var solu = document.createElement('td');

	if (arguments[0] != '') solu.className = arguments[0];

	for (var i=1; i<arguments.length; i++) {
		var tmp = arguments[i];

		if (typeof tmp == "string") {
			solu.appendChild(document.createTextNode(tmp));
		}
		else {
			solu.appendChild(tmp);
		}
	}

	return solu;
}

function tee_rivi(tunnus, laina, nimi, laji, latin, alkupera, vari) {
	var rivi = document.createElement('tr');

	var linkki = document.createElement('a');
	linkki.href = '#' + tunnus;
	linkki.addEventListener('click', yksityiskohdat);
	linkki.appendChild(document.createTextNode(nimi));

	rivi.appendChild(tee_solu('tunnus', tunnus));
	rivi.appendChild(tee_solu('laina', (laina == 't')? '✓': ''));
	rivi.appendChild(tee_solu('nimi', linkki));
	rivi.appendChild(tee_solu('laji', latin, ', ', laji));
	rivi.appendChild(tee_solu('alkup', alkupera));
	rivi.appendChild(tee_solu('vari', vari));

	return rivi;
}

function kaarmeile(data) {
	var taulu = $('<table></table>');

	data.forEach(function(e, i, a) {
		taulu.append(tee_rivi(e.id, e.laina, e.nimi, e.laji, e.latin, e.alkupera, e.vari));
	});

	$('body').append(taulu);
}

function handlaa(data) {
	$(document).ready(function() {
		kaarmeile(data);
	});
}
