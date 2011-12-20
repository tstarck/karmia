/* isohali.js */

var ylennys    = "Oletko varma, että haluat tehdä käyttäjästä ylläpedon?";
var kayttajako = "Oletko varma, että haluat poistaa käyttäjän?";
var kaarmeko   = "Oletko varma, että haluat poistaa käärmeen?";
var lajiko     = "Lajin poistaminen vaikuttaa käämeisiin, jotka ovat kyseistä "
               + "lajia. Oletko varma, että haluat poistaa koko lajin?";

function varmistus(a, kysymys) {
	$(a).on('click', function(tapahtuma) {
		if (!window.confirm(kysymys)) {
			tapahtuma.preventDefault();
		}
	});
}

$(document).ready(function() {
	$.each($('table#kayttajat td.promoa a'), function(i, ankkuri) {
		varmistus(ankkuri, ylennys);
	});
	$.each($('table#kayttajat td.poista a'), function(i, ankkuri) {
		varmistus(ankkuri, kayttajako);
	});
	$.each($('table#kaarmeet td.poista a'), function(i, ankkuri) {
		varmistus(ankkuri, kaarmeko);
	});
	$.each($('table#lajit td.poista a'), function(i, ankkuri) {
		varmistus(ankkuri, lajiko);
	});
});
