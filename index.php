<?php

require_once 'auth.php';
require_once 'common.php';
require_once 'kaarme.php';

class INDEX {
	/* Oletuskonstruktori:
	 * 1) Tarkastetaan käyttäjä ja oikeudet,
	 * 2) parsitaan syöte ja
	 * 3) tehdään jotain järkevää edellisten perusteella.
	 */
	function __construct() {
		$lupa = new AUTH;

		if (!$lupa->ok()) {
			header("Location: kirjaudu.xhtml");
			exit;
		}

		with(new KAARME($lupa->kayttaja()))->juokse();

		if (!headers_sent()) {
			header("Content-Type: application/xhtml+xml; charset=utf-8");
			readfile("json.xhtml");
		}

		exit;
	}
}

new INDEX;

?>
