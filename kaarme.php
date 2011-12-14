<?php

require_once 'xhtml.php';
require_once 'common.php';

$matokysely = <<<MATO
SELECT k.id,
       k.nimi,
       l.nimi AS laji,
       l.latin,
       a.alkupera,
       l.vari,
       l.myrkyllisyys,
       l.uhanalaisuus
FROM   kaarmeet k,
       lajit l,
       alkupera a
WHERE  k.id = %s AND
       k.laji = l.id AND
       l.alkupera = a.id
MATO;

$lainakysely = <<<LAINA
SELECT l.id,
       l.lainaaja
FROM   kaarmeet k,
       lainat l
WHERE  k.id = %s AND
       l.kaarme = k.id AND
       l.loppu IS NULL
LAINA;

class KAARME {
	private $kaarme;
	private $tunnus;

	/* Oletuskonstruktori, joka parsii syötteen.
	 */
	function __construct($tunnus) {
		$this->kaarme = hae_oikea_arvo("kaarme", "/^\d+$/");
		$this->tunnus = $tunnus;
	}

	/* Rakas virhesivumme
	 */
	private function neljanollanelja() {
		header("HTTP/1.1 404 Not Found");
		with(new XHTML("404"))->otsikko("404 Snake not found");
		exit;
	}

	public function juokse() {
		global $matokysely, $lainakysely;

		if ($this->kaarme === false) return;

		/* FIXME
		 * Jos käyttäjä on ylläpeto,
		 * näytä koko lainahistoria.
		 */

		$kanto = new PGDB;

		$mato = $kanto->kysele(sprintf($matokysely, $this->kaarme))->anna_kaikki()->taulukkona();

		if (empty($mato)) {
			$this->neljanollanelja();
		}

		$mato = $mato[0];
		$nimi = $mato["nimi"];

		$sivu = with(new XHTML("Karmia > ".$nimi))->otsikko("Karmia > ".$nimi);

		$laina = $kanto->kysele(sprintf($lainakysely, $this->kaarme, $this->tunnus))->anna_kaikki()->taulukkona();

		if (empty($laina)) {
			$sivu->linkki("Lainaa", "?lainaa=".$mato["id"]);
		}
		elseif ($laina[0]["lainaaja"] === $this->tunnus) {
			$sivu->kappale("Sulla on käärme.");
			$sivu->linkki("Palauta se tästä!", "?palauta=".$mato["id"]);
		}
		else {
			$sivu->kappale("Käärme ei ole lainattavissa.");
		}

		exit;
	}
}

?>
