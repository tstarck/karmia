<?php

require_once 'auth.php';
require_once 'pgdb.php';
require_once 'common.php';

class JSON {
	private $lupa;
	private $kysely = "SELECT kaarmeet.nimi, lajit.nimi AS laji, lajit.latin, alkupera.alkupera, lajit.vari FROM kaarmeet, lajit, alkupera WHERE kaarmeet.laji = lajit.id AND lajit.alkupera = alkupera.id";

	/* Oletuskonstruktori:
	 * Tarkistetaan, onko käyttäjällä multipass.
	 */
	function __construct() {
		$this->lupa = with(new AUTH)->ok();
	}

	function madot() {
		if (!$this->lupa) return;

		header("Content-Type: application/json");
		echo "handlaa(", json_encode(with(new PGDB)->kysele($this->kysely)->anna_kaikki()->taulukkona()), ")";
	}
}

$json = new JSON();

$json->madot();

?>
