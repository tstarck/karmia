/* lomake.js */

var err_tunnus = "Tunnuksen tulee olla korkeintaan 8 merkkiä eikä se saa alkaa numerolla.";
var err_salasana = "Annetut salasanat eivät täsmää.";
var err_kirjautuminen = "Väärä tunnus tai salasana.";

function kirjaa_virhe(msg) {
	var virheloota = document.getElementById('virheloki');
	var paragraph = document.createElement('p');
	var text = document.createTextNode(msg);

	if (virheloota == null) return;

	paragraph.appendChild(text);
	virheloota.appendChild(paragraph);
	virheloota.style.display = 'block';
}

function tarkista_virheet() {
	var vihreet = window.location.hash.substring(1).split(',');

	for (var i=0; i<vihreet.length; i++) {
		switch (vihreet[i]) {
		case "virheellinen_kirjautuminen":
			kirjaa_virhe(err_kirjautuminen);
			break;
		case "huono_tunnus":
			kirjaa_virhe(err_tunnus);
			break;
		case "sovittamattomat_salasanat":
			kirjaa_virhe(err_salasana);
			break;
		}
	}
}

function tarkista_paritus() {
	var eka   = document.getElementById('passone');
	var toka  = document.getElementById('passtwo');
	var virhe = document.getElementById('virheilmo');

	if (eka.value == "" || toka.value == "") {
		virhe.style.display = "none";
	}
	else if (eka.value.length <= toka.value.length && eka.value != toka.value) {
		virhe.style.display = "inline";
	}
	else {
		virhe.style.display = "none";
	}
}

window.addEventListener("load", function() {
	var setoinen = document.getElementById('passtwo');

	if (setoinen != null) {
		setoinen.addEventListener('keyup', tarkista_paritus);
	}

	tarkista_virheet();
});
