<?php

/* Pieni kelmuluokka PHP:n PostgreSQL-funktioille. Avaa ja
 * sulkee tietokantayhteyden automaagisesti olion luonnin
 * ja hävityksen yhteydessä.
 */

/* Asetustiedosto, josta täytyy löytyä pg_connect:lle
 * kelpaava muuttuja $_karmia_db_connection.
 */
require_once 'config/karmia.php';

class PGDB {
	private $yhteys;
	private $vastaus;
	private $kama;

	/* Alustaa olion ja avaan tietokantayhteyden.
	 */
	function __construct() {
		global $_karmia_db_connection;
		$this->vastaus = false;
		$this->yhteys = pg_connect($_karmia_db_connection);
	}

	/* Hävittää olio ja sulkee yhteyden.
	 */
	function __destruct() {
		pg_close($this->yhteys);
	}

	/* Sanitoi annetun syötteen tietokantaa varten.
	 */
	private function sanitoi($likapyykki) {
		return pg_escape_string($likapyykki);
	}

	/* Suorittaa tietokantakyselyn, kunhan yhteys on avattu.
	 *
	 * Metodi hyväksyy 1–4 argumenttia.
	 *
	 * Ensimmäinen argumentti tulee olla joko puhdas SQL-kysely tai
	 * printf:lle annettava SQL-kyselyn muotoilu. Kummassakaan tapauksessa
	 * argumentin TULEE EHDOTTOMASTI OLLA SANITOITU tai muutoin turvallinen.
	 *
	 * Ensimmäistä seuraavat argumentit – jos sellaisia on annettu –
	 * sanitoidaan ja niitä käytetään lopullisen SQL-kyselyn muodostamiseen.
	 */
	public function kysele() {
		if (func_num_args() <= 0) {
			error_log("kysele() kutsuttu ilman argumentteja");
			die;
		}

		$likaiset = func_get_args();
		$muoto = array_shift($likaiset);
		$pesty = array_map(array($this, "sanitoi"), $likaiset);

		/* Tää ois kiva tehdä jotenkin elegantimmin, mutta kuinka?
		 * Ainakaan sprintf() ei suostu nomnommaamaan taulukkoa
		 * sellaisenaan :-\
		 */
		switch (count($pesty)) {
			case 0: $kysely = $muoto; break;
			case 1: $kysely = sprintf($muoto, $pesty[0]); break;
			case 2: $kysely = sprintf($muoto, $pesty[0], $pesty[1]); break;
			case 3: $kysely = sprintf($muoto, $pesty[0], $pesty[1], $pesty[2]); break;
			case 4: $kysely = sprintf($muoto, $pesty[0], $pesty[1], $pesty[2], $pesty[3]); break;
			case 5: $kysely = sprintf($muoto, $pesty[0], $pesty[1], $pesty[2], $pesty[3], $pesty[4]); break;
			case 6: $kysely = sprintf($muoto, $pesty[0], $pesty[1], $pesty[2], $pesty[3], $pesty[4], $pesty[5]); break;
			default:
				error_log("kysely(args > 6) on toteuttamatta");
				die;
		}

		if ($this->yhteys !== false) {
			$this->vastaus = pg_query($this->yhteys, $kysely);

			if ($this->vastaus === false) {
				error_log($kysely);
			}
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
