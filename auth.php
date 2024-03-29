<?php

require_once 'common.php';
require_once 'pgdb.php';
require_once 'sql.php';

/* Luokka, joka pitää visusti huolta käyttäjien
 * tunnistamisesta. On tarvittaessa hyvin ankara.
 */
class AUTH {
	private $kayttaja;
	private $yllapitelija;
	private $luontiaika;

	/* Oletuskonstruktori:
	 * Olion luonnin yhteydessä mennään suoraan asiaan ja
	 * tarkistaan käyttäjän paperit eli ajetaan pikkuleipä-
	 * tiedot tietokannan kautta ja tsekataan, onko käyttäjä
	 * kosher.
	 */
	function __construct() {
		global $_sql_auth_tunnistus;

		$this->kayttaja = false;
		$this->yllapitelija = false;
		$this->luontiaika = false;

		$user = hae_pipari("user");
		$pass = hae_pipari("pass");

		if (empty($user) or empty($pass)) {
			return;
		}

		$vastaus = with(new PGDB)->kysele($_sql_auth_tunnistus, $user, $pass)->anna_rivi()->taulukkona();

		if ($vastaus === false) return;  // ei käyttäjää
		if (empty($vastaus))    return;  // ei koko taulua tms.

		$this->kayttaja = $vastaus["tunnus"];
		$this->luontiaika = $vastaus["luotu"];

		if ($vastaus["yllapeto"] === "t") {
			$this->yllapitelija = true;
		}

		if (!headers_sent()) {
			aseta_pipari("user", $user);
			aseta_pipari("pass", $pass);
		}
	}

	/* Kertoo, onko käyttäjä tunnistettu eli palauttaa
	 * totuusarvon true tai false sen mukaan onko käyttäjä
	 * tunnistettu vai ei.
	 */
	public function ok() {
		return ($this->kayttaja !== false);
	}

	/* Kertoo, kuten yllä, onko käyttäjällä ylläpeto-oikeudet.
	 */
	public function yllapeto() {
		return ($this->yllapitelija);
	}

	/* Palauttaa käyttäjän tunnuksen.
	 */
	public function kayttaja() {
		return $this->kayttaja;
	}

	/* Palauttaa tunnuksen luontiajankohdan.
	 */
	public function luontiaika() {
		return $this->luontiaika;
	}
}

?>
