<?php

require_once 'pgdb.php';
require_once 'common.php';

/* Luokka, joka pitää visusti huolta käyttäjien
 * tunnistamisesta. On tarvittaessa hyvin ankara.
 */
class AUTH {
	private $valid_user;
	private $username;
	private $is_admin;
	private $errors;

	private $sql_query = "SELECT tunnus, yllapeto FROM kayttajat WHERE tunnus = '%s' AND salasana = '%s'";

	/* Oletuskonstruktori:
	 * Olion luonnin yhteydessä mennään suoraan asiaan ja
	 * tarkistaan käyttäjän paperit eli ajetaan pikkuleipä-
	 * tiedot tietokannan kautta. Tietokannan pitäisi sitten
	 * kertoa, onko käyttäjä kosher.
	 */
	function __construct() {
		// $debug = new DEBUG();

		$this->valid_user = false;
		$this->is_admin = false;
		$this->errors = array();

		$user = pg_escape_string(hae_pipari("user"));
		$pass = pg_escape_string(hae_pipari("pass"));

		if (empty($user) and empty($pass)) {
			return;
		}

		// $debug->debug(sprintf("[u/p]:: %s / %s", $user, $pass));

		$kanto = new PGDB();

		if (!$kanto->ok()) die; // FIXME

		$kysely = sprintf($this->sql_query, $user, $pass);

		if ($kanto->query($kysely)) {
			$vastaus = $kanto->getline();

			if (!empty($vastaus)) {
				// $debug->debug(" :-) :: ".$vastaus["tunnus"]." on tunnistettu");

				$this->valid_user = true;
				$this->username = $vastaus["tunnus"];
			}
			else {
				array_push($this->errors, "virheellinen_kirjautuminen");
			}

			if ($vastaus["yllapeto"] === true) {
				// $debug->debug(" ^_^ :: ja jopa admin");

				$this->is_admin = true;
			}
		}
		else {
			die;; // FIXME
		}
	}

	/* void
	 */
	function __destruct() {
	}

	/* Kertoo, onko käyttäjä tunnistettu eli palauttaa
	 * totuusarvon true tai false jos käyttäjä on
	 * tunnistettu tai ei.
	 */
	public function ok() {
		return $this->valid_user;
	}

	/* Kertoo, kuten yllä, onko käyttäjällä ylläpeto-oikeudet.
	 */
	public function admin() {
		return $this->is_admin;
	}

	/* Palauttaa katenoidun merkkijonon kohdattuja virheitä.
	 */
	public function err_str() {
		return join(",", $this->errors);
	}
}

?>
