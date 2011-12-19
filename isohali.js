/* isohali.js */

var varmistus = "Ootko varma, ettet klikannut linkkiä vahingossa?";

$(document).ready(function() {
	$.each($('td.poista > a'), function(i, ankkuri) {
		$(ankkuri).on('click', function(tapahtuma) {
			if (!window.confirm(varmistus)) {
				tapahtuma.preventDefault();
			}
		});
	});
});
