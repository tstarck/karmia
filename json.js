/* json.js */

var tieto = null;

function lainaa(tapahtuma) {
	var id = tapahtuma.data;
	console.log("Lainaa " + id);
}

function palauta(tapahtuma) {
	var id = tapahtuma.data;
	console.log("Palauta " + id);
}

function yksityiskohdat(tapahtuma) {
	var otus = tapahtuma.data;

	var nappi = $('<input />').attr('type', 'button');

	switch (otus.laina) {
		case 'sulla':   nappi.attr('value', 'Palauta').on('click', null, otus.id, palauta); break;
		case 'varattu': nappi.attr('value', 'Lainaa').attr('disabled', 'disabled'); break;
		case 'vapaa':   nappi.attr('value', 'Lainaa').on('click', null, otus.id, lainaa); break;
	}

	var popup = $('<tr></tr>').addClass('popup').append(
		$('<td></td>').attr('colspan', '2'),
		$('<td></td>').attr('colspan', '3').append(
			nappi,
			$('<p></p>').append($('<b></b>').text('Väri: '), otus.vari),
			$('<p></p>').append($('<b></b>').text('Alkuperä: '), otus.alkupera),
			$('<p></p>').append($('<b></b>').text('Myrkyllisyys: '), otus.myrkyllisyys),
			$('<p></p>').append($('<b></b>').text('Uhanalaisuus: '), otus.uhanalaisuus)
		)
	);

	$('.popup').remove();

	$(tapahtuma.target.parentNode.parentNode).after(popup);
}

function rivittele(index, otus) {
	index++;

	var rivi = $('<tr></tr>');

	rivi.solu = function(luokka, asia) {
		if (typeof asia == 'string') this.append($('<td></td>').addClass(luokka).text(asia));
		else                         this.append($('<td></td>').addClass(luokka).append(asia));
	};

	otus.symboloi = function() {
		var abbr = $('<abbr></abbr>');
		switch (this.laina) {
			case 'sulla':   return abbr.attr('title', 'Se on sulla').text('✓');
			case 'varattu': return abbr.attr('title', 'Se on varattu').text('✗');
			default:        return $('');
		}
	}

	rivi.solu('nro', index + '.');
	rivi.solu('laina', otus.symboloi());
	rivi.solu('nimi', $('<a></a>').on('click', null, otus, yksityiskohdat).text(otus.nimi));
	rivi.solu('laji', otus.laji);
	rivi.solu('laji latin', otus.latin);

	return rivi;
}

function kaarmeile(tieto) {
	var taulu = $('<table></table>');

	tieto.forEach(function(otus, index) {
		taulu.append(rivittele(index, otus));
	});

	$('body').append(taulu);
}

function handlaa(json) {
	$(document).ready(function() {
		kaarmeile(json);
	});
}
