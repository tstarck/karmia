<?php

require_once 'auth.php';
require_once 'sql.php';
require_once 'xhtml.php';

class OMA {
	private $tunnistus;

	function __construct() {
		$this->tunnistus = new AUTH;

		if (!$this->tunnistus->ok()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
		}
	}

	public function sivu() {
		global $_sql_oma_lainat;

		$tunnus = $this->tunnistus->kayttaja();

		$sivu = new XHTML("Karmia > Oma sivu", "isohali.css");

		$sivu->kappale(
			($this->tunnistus->yllapeto())? "Ylläpitäjä: ": "Käyttäjä: ",
			"<b>", $tunnus, "</b>"
		);

		$lainat = with(new PGDB)->kysele($_sql_oma_lainat, $tunnus)->anna_kaikki()->taulukkona();

		$sivu->taulukoi(
			"Lainahistoria.",
			array("id" => "#", "nimi" => "Käärme", "alku" => "Lainattu", "loppu" => "Palautettu"),
			$lainat
		);
	}
}

with(new OMA)->sivu();

?>
