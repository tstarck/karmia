<?php

require_once 'config/karmia.php';
require_once 'xhtml.php';
require_once 'common.php';

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
		$this->lainaus = hae_oikea_arvo("lainaa", "/^\d+$/");
		$this->palautus = hae_oikea_arvo("palauta", "/^\d+$/");
	}

	public function handlaa() {
		if ($this->lainaus !== false) {
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

	/* Lainaa käärme.
	 */
	private function lainaa() {
		global $_karmia_root, $lainauskysely;
		with(new PGDB)->kysele($lainauskysely, $this->lainaus, $this->tunnus);
		header("Content-Type: text/plain");
		echo "200 OK";
	}

	/* Palauta käärme.
	 */
	private function palauta() {
		global $_karmia_root, $palautuskysely;
		with(new PGDB)->kysele($palautuskysely, $this->tunnus, $this->palautus);
		header("Content-Type: text/plain");
		echo "200 OK";
	}
}

?>
