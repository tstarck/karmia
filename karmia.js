/* karmia.js */

function kirjaa_virhe(msg) {
	var virheloota = document.getElementById('errorreseptor');
	var paragraph = document.createElement('p');
	var text = document.createTextNode(msg);

	paragraph.appendChild(text);
	virheloota.appendChild(paragraph);
	virheloota.style.display = 'block';
}

function tarkista_virheet() {
	var vihreet = window.location.hash.substring(1).split(',');

	for (var i=0; i<vihreet.length; i++) {
		switch (vihreet[i]) {
		case "invalid_username":
			kirjaa_virhe("Käyttäjänimi tarttee olla [a-z][0-9a-z_]+");
			break;
		case "password_mismatch":
			kirjaa_virhe("Salasanat eivät täsmää. Oleppas hiukka huolellisempi.");
			break;
		}

		console.log("hih :: " + vihreet[i]);
	}
}

window.addEventListener("load", tarkista_virheet);
