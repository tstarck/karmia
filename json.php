<?php

require_once 'auth.php';
require_once 'pgdb.php';
require_once 'common.php';

$kysely = <<<KYSELY
SELECT k.id,
       k.nimi,
       l.laji,
       l.latin,
       a.alkupera,
       l.vari,
       m.myrkyllisyys,
       l.uhanalaisuus,
       CASE WHEN t.lainaaja = '%s' THEN 'sulla'
            WHEN t.lainaaja IS NOT NULL THEN 'varattu'
            ELSE 'vapaa'
       END AS laina
FROM   lajit l,
       alkupera a,
       myrkyllisyys m,
       kaarmeet k
       LEFT OUTER JOIN lainat t
       ON k.id = t.kaarme AND
          t.loppu IS NULL
WHERE  k.laji = l.id AND
       l.alkupera = a.id AND
       l.myrkyllisyys = m.id
KYSELY;

class JSON {
	private $tunnistus;

	/* Oletuskonstruktori:
	 * Alustetaan käyttäjän tunnistus.
	 */
	function __construct() {
		$this->tunnistus = new AUTH;
	}

	function json() {
		global $kysely;

		if (!$this->tunnistus->ok()) return;

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Content-Type: application/javascript");

		echo "handlaa(", json_encode(
			with(new PGDB)
			->kysele($kysely, $this->tunnistus->kayttaja())
			->anna_kaikki()
			->taulukkona()
		), ")";
	}
}

with(new JSON)->json();

?>
