/* json.js */

var tieto = null;

function yksityiskohdat(tapahtuma) {
	var i = tapahtuma.data[0];
	var n = tapahtuma.data[1];

	var nappi = $('<input />').attr('type', 'button').attr('value', 'Lainaa').on('click', function() {
		window.location = '?kaarme=' + n;
	});

	var popup = $('<tr></tr>').addClass('popup').append(
		$('<td></td>').attr('colspan', '2'),
		$('<td></td>').attr('colspan', '3').append(
			nappi,
			$('<p></p>').append($('<b></b>').text('Väri: '), tieto[i].vari),
			$('<p></p>').append($('<b></b>').text('Alkuperä: '), tieto[i].alkupera),
			$('<p></p>').append($('<b></b>').text('Uhanalaisuus: '), tieto[i].uhanalaisuus)
		)
	);

	$('.popup').remove();

	$(tapahtuma.target.parentNode.parentNode).after(popup);
}

function rivittele(i, tunnus, laina, nimi, laji, latin) {
	var rivi = $('<tr></tr>');

	laina = (laina == 't')? '✓': ''; // U+2713 U+2717

	rivi.append($('<td></td>').addClass('tunnus').text(tunnus + '.'));
	rivi.append($('<td></td>').addClass('laina').append(laina));

	rivi.append($('<td></td>').addClass('nimi').append(
		$('<a></a>').on('click', null, [i, tunnus], yksityiskohdat).text(nimi)
	));

	rivi.append($('<td></td>').addClass('laji').append(laji));
	rivi.append($('<td></td>').addClass('laji latin').append(latin));

	return rivi;
}

function kaarmeile() {
	var taulu = $('<table></table>');

	tieto.forEach(function(e, i, a) {
		taulu.append(rivittele(i, e.id, e.laina, e.nimi, e.laji, e.latin));
	});

	$('body').append(taulu);
}

function handlaa(json) {
	tieto = json;
	$(document).ready(kaarmeile);
}
