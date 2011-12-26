<?php

require_once 'config/karmia.php';
require_once 'auth.php';
require_once 'common.php';

/* Anna json-muotoista raakadataa, joista parsitaan kasaan
 * joka sivulla navigointiin käytetty linkkilista.
 */
class LINKKILISTA {
	private $yllapeto;

	function __construct() {
		/* Tarkistetaan ylläpeto-status.
		 *
		 * Myös tunnistautumaton käyttäjä saa json-paketin, jos
		 * keksii kysyä, muttei välitetä siitä, sillä tän on
		 * tarkoitus olla pelkkää UI / käytettävyyssäätöä.
		 */
		$this->yllapeto = with(new AUTH)->yllapeto();
	}

	public function linkit() {
		global $_karmia_root;

		$linkit = array(
			array(true,   "oma.php",      "Oma sivu"),
			array(true,   $_karmia_root,  "Käärmeet"),
			array(false,  "isohali.php",  "Isohali"),
			array(true,   "pois.php",     "Kirjaudu ulos")
			//    ^-- Jos false, linkki on ylläpedon
		);

		header("Content-Type: application/javascript");
		echo "linkita(", json_encode(array_filter($linkit, array($this, "ehto"))), ")";
	}

	/* Karsi ylläpedon linkit, mikäli käyttäjä ei ole ylläpeto.
	 */
	private function ehto($tapaus) {
		return ($this->yllapeto or $tapaus[0]);
	}
}

with(new LINKKILISTA)->linkit();

?>
