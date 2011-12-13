/* json.js */

function yksityiskohdat(tapahtuma) {
	console.log("yksityiskohtia :: " + tapahtuma.target.text);
}

function rivittele(tunnus, laina, nimi, laji, latin, alkup, vari) {
	var rivi = $('<tr></tr>');

	laina = (laina == 't')? '✗': ''; // U+2717 U+2713

	rivi.append($('<td></td>').addClass('tunnus').text(tunnus + '.'));
	rivi.append($('<td></td>').addClass('laina').append(laina));

	rivi.append($('<td></td>').addClass('nimi').append(
		$('<a></a>').attr('href', '#' + tunnus).on('click', yksityiskohdat).text(nimi)
	));

	rivi.append($('<td></td>').addClass('laji').
		append($('<i></i>').text(latin)).
		append(', ' + laji)
	);

	rivi.append($('<td></td>').addClass('alkup').text(alkup));
	rivi.append($('<td></td>').addClass('vari').text(vari));

	return rivi;
}

function kaarmeile(data) {
	var taulu = $('<table></table>');

	data.forEach(function(e, i, a) {
		taulu.append(rivittele(e.id, e.laina, e.nimi, e.laji, e.latin, e.alkupera, e.vari));
	});

	$('body').append(taulu);
}

function handlaa(data) {
	$(document).ready(function() { kaarmeile(data); });
}
