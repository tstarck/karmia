<?php

/* Pieni kelmuluokka PHP:n PostgreSQL-funktioille. Avaa ja
 * sulkee tietokantayhteyden automaagisesti olion luonnin
 * ja hävityksen yhteydessä.
 */

/* Asetustiedosto, josta täytyy löytyä pg_connect:lle
 * kelpaava muuttuja $__karmia_db_config.
 */
require 'config/db.php';

class PGDB {
	private $resource;
	private $connection;

	/* Alustaa olion ja avaan tietokantayhteyden.
	 */
	function __construct() {
		$this->resource = false;
		$this->connection = pg_connect($__karmia_db_config);
	}

	/* Hävittää olio ja sulkee yhteyden.
	 */
	function __destruct() {
		pg_close($connection);
	}

	/* Kertoo, onko kaikki varmasti kunnossa.
	 */
	public function ok() {
		return ($connection !== false);
	}

	/* Suorittaa tietokantakyselyn, joka annetaan argumentiksi.
	 * Palauttaa toden, mikäli kysely onnistui. Muutoin epätoden.
	 */
	public function query($query) {
		if ($this->ok()) {
			$this->resource = pg_query($this->connection, $query);
		}

		return ($this->resource !== false);
	}

	/* Palauttaa kaikki edellisen tietokantakyselyn tietueet.
	 */
	public function getall() {
		return pg_fetch_all($this->resource);
	}

	/* Palauttaa seuraavan rivin edellisestä vastauksesta.
	 */
	public function getline() {
		return pg_fetch_assoc($this->resource);
	}
}

?>
