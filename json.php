<?php

require_once 'auth.php';
require_once 'pgdb.php';
require_once 'common.php';
require_once 'sql.php';

class JSON {
	private $tunnistus;

	/* Oletuskonstruktori:
	 * Alustetaan käyttäjän tunnistus.
	 */
	function __construct() {
		$this->tunnistus = new AUTH;
	}

	function json() {
		global $_sql_json_kaikkimullehetinyt;

		if (!$this->tunnistus->ok()) return;

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Content-Type: application/javascript");

		echo "handlaa(", json_encode(
			with(new PGDB)
			->kysele($_sql_json_kaikkimullehetinyt, $this->tunnistus->kayttaja())
			->anna_kaikki()
			->taulukkona()
		), ")";
	}
}

with(new JSON)->json();

?>
