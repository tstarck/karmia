<?php

require_once 'auth.php';
require_once 'pgdb.php';
require_once 'common.php';

$kysely = <<<KYSELY
SELECT k.id,
       k.nimi AS nimi,
       l.nimi AS laji,
       l.latin,
       a.alkupera,
       l.vari,
       l.uhanalaisuus,
       COALESCE(CAST(t.id AS bool), false) AS laina
FROM   lajit l,
       alkupera a,
       kaarmeet k
       LEFT OUTER JOIN lainat t ON k.id = t.kaarme AND t.loppu IS NULL
WHERE  k.laji = l.id AND
       l.alkupera = a.id
KYSELY;

class JSON {
	private $lupa;

	/* Oletuskonstruktori:
	 * Tarkistetaan, onko käyttäjällä multipass.
	 */
	function __construct() {
		$this->lupa = with(new AUTH)->ok();
	}

	function json() {
		global $kysely;

		if (!$this->lupa) return;

		header("Content-Type: application/json");
		echo "handlaa(", json_encode(with(new PGDB)->kysele($kysely)->anna_kaikki()->taulukkona()), ")";
	}
}

with(new JSON)->json();

?>
