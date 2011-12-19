<?php

/* Pieni kelmuluokka PHP:n PostgreSQL-funktioille. Avaa ja
 * sulkee tietokantayhteyden automaagisesti olion luonnin
 * ja hävityksen yhteydessä.
 */

/* Asetustiedosto, josta täytyy löytyä pg_connect:lle
 * kelpaava muuttuja $_karmia_db_config.
 */
require_once 'config/karmia.php';

class PGDB {
	private $yhteys;
	private $vastaus;
	private $kama;

	/* Alustaa olion ja avaan tietokantayhteyden.
	 */
	function __construct() {
		global $_karmia_db_config;
		$this->vastaus = false;
		$this->yhteys = pg_connect($_karmia_db_config);
	}

	/* Hävittää olio ja sulkee yhteyden.
	 */
	function __destruct() {
		pg_close($this->yhteys);
	}

	/* Suorittaa tietokantakyselyn, kunhan yhteys on avattu.
	 */
	public function kysele($kysely = false) {
		if ($kysely and $this->yhteys !== false) {
			$this->vastaus = pg_query($this->yhteys, $kysely);
		}
		return $this;
	}

	/* Ottaa tietokantavastauksesta yhden rivin.
	 */
	public function anna_rivi() {
		if ($this->vastaus !== false) {
			$this->kama = pg_fetch_assoc($this->vastaus);
		}
		return $this;
	}

	/* Ottaa tietokantavastauksesta kaiken, minkä irti saa.
	 */
	public function anna_kaikki() {
		if ($this->vastaus !== false) {
			$this->kama = pg_fetch_all($this->vastaus);
		}
		return $this;
	}

	/* Palauttaa vastauksen taulukossa.
	 */
	public function taulukkona() {
		return $this->kama;
	}
}

?>
