<?php

require_once 'config/karmia.php';
require_once 'common.php';
require_once 'sql.php';
require_once 'xhtml.php';

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
		global $_karmia_root, $_sql_lainaa_kaarme;
		with(new PGDB)->kysele($_sql_lainaa_kaarme, $this->lainaus, $this->tunnus);
		header("Content-Type: text/plain");
		echo "200 OK";
	}

	/* Palauta käärme.
	 */
	private function palauta() {
		global $_karmia_root, $_sql_palauta_kaarme;
		with(new PGDB)->kysele($_sql_palauta_kaarme, $this->tunnus, $this->palautus);
		header("Content-Type: text/plain");
		echo "200 OK";
	}
}

?>
