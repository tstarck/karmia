<?php

require_once 'config/karmia.php';
require_once 'auth.php';
require_once 'common.php';

class LINKKILISTA {
	private $yllapeto;

	function __construct() {
		global $_karmia_root;

		$this->yllapeto = with(new AUTH)->yllapeto();

		$linkit = array(
			array(true,   "oma.php",      "Oma sivu"),
			array(true,   $_karmia_root,  "Käärmeet"),
			array(false,  "isohali.php",  "Isohali"),
			array(true,   "pois.php",     "Kirjaudu ulos")
		);

		header("Content-Type: application/javascript");
		echo "linkita(", json_encode(array_filter($linkit, array($this, "ehto"))), ")";
	}

	private function ehto($tapaus) {
		return ($this->yllapeto or $tapaus[0]);
	}
}

new LINKKILISTA;

?>
