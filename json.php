<?php

require_once 'auth.php';
require_once 'pgdb.php';
require_once 'common.php';

class JSON {
	private $lupa;
	private $kysely = "SELECT k.id, k.nimi AS nimi, l.nimi AS laji, l.latin, a.alkupera, l.vari, COALESCE(CAST(t.id AS bool), false) AS laina FROM lajit l, alkupera a, kaarmeet k LEFT OUTER JOIN lainat t ON k.id = t.kaarme WHERE k.laji = l.id AND l.alkupera = a.id";

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
