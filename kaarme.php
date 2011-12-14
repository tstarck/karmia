<?php

require_once 'config/karmia.php';
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

$tietokysely = <<<TIETO
SELECT l.id,
       l.lainaaja
FROM   kaarmeet k,
       lainat l
WHERE  k.id = %s AND
       l.kaarme = k.id AND
       l.loppu IS NULL
TIETO;

$lainauskysely = <<<LAINA
INSERT INTO lainat (kaarme, lainaaja) VALUES (%s, '%s')
LAINA;

$palautuskysely = <<<PALAUTUS
UPDATE lainat
SET    loppu = CURRENT_TIMESTAMP
WHERE  lainaaja = '%s' AND
       kaarme = %s AND
       loppu IS NULL
PALAUTUS;

class KAARME {
	private $tunnus;
	private $kaarme;
	private $lainaus;
	private $palautus;

	/* Oletuskonstruktori, joka parsii syötteen.
	 */
	function __construct($tunnus) {
		$this->tunnus = $tunnus;
		$this->kaarme = hae_oikea_arvo("kaarme", "/^\d+$/");
		$this->lainaus = hae_oikea_arvo("lainaa", "/^\d+$/");
		$this->palautus = hae_oikea_arvo("palauta", "/^\d+$/");
	}

	public function handlaa() {
		if ($this->kaarme !== false) {
			$this->vaihtoehdotus();
		}
		elseif ($this->lainaus !== false) {
			$this->lainaa();
		}
		elseif ($this->palautus !== false) {
			$this->palauta();
		}
		else {
			/* Ei meillä ollutkaan mitään asiaa tänne.
			 * Palautetaan suoritus pääsivulle.
			 */
			return;
		}

		exit;
	}

	/* Rakas virhesivumme
	 */
	private function neljanollanelja() {
		header("HTTP/1.1 404 Not Found");
		with(new XHTML("404"))->otsikko("404 Snake not found");
		exit;
	}

	private function vaihtoehdotus() {
		global $matokysely, $tietokysely;

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

		$tiedot = $kanto->kysele(sprintf($tietokysely, $this->kaarme, $this->tunnus))->anna_kaikki()->taulukkona();

		if (empty($tiedot)) {
			$sivu->linkki("Lainaa", "?lainaa=".$mato["id"]);
		}
		elseif ($tiedot[0]["lainaaja"] === $this->tunnus) {
			$sivu->kappale("Sulla on käärme.");
			$sivu->linkki("Palauta se tästä!", "?palauta=".$mato["id"]);
		}
		else {
			$sivu->kappale("Käärme ei ole lainattavissa.");
		}
	}

	/* Lainaa käärme.
	 */
	private function lainaa() {
		global $__karmia_root, $lainauskysely;
		with(new PGDB)->kysele(sprintf($lainauskysely, $this->lainaus, $this->tunnus));
		header("Location: ".$__karmia_root);
	}

	/* Palauta käärme.
	 */
	private function palauta() {
		global $__karmia_root, $palautuskysely;
		with(new PGDB)->kysele(sprintf($palautuskysely, $this->tunnus, $this->palautus));
		header("Location: ".$__karmia_root);
	}
}

?>
