/* linkit.js */

function linkita(linkit) {
	$(document).ready(function() {
		$.each(linkit, function(i, linkki) {
			$('#linkit').append($('<li></li>').append(
				$('<a></a>').attr('href', linkki[1]).text(linkki[2])
			));
		});
	});
}
