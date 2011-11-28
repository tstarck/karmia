<?php

require 'pgdb.php';
require 'common.php';
require 'debug.php';

$tarkistuskysely = "SELECT tunnus, yllapeto FROM kayttajat WHERE tunnus = '%s' AND salasana = '%s'";

/* Luokka, joka pitää visusti huolta käyttäjien
 * tunnistamisesta. On tarvittaessa hyvin ankara.
 */
class AUTH {
	private $valid_user;
	private $username;
	private $is_admin;

	/* Oletuskonstruktori:
	 * Olion luonnin yhteydessä mennään suoraan asiaan ja
	 * tarkistaan käyttäjän paperit eli ajetaan pikkuleipä-
	 * tiedot tietokannan kautta. Tietokannan pitäisi sitten
	 * kertoa, onko käyttäjä kosher.
	 */
	function __construct() {
		$debug = new DEBUG();

		$this->valid_user = false;
		$this->is_admin = false;

		$user = pg_escape_string(get_cookie("user"));
		$passwd = pg_escape_string(get_cookie("pass"));

		$debug->debug(sprintf("[u/p]:: %s / %s", $user, $passwd));

		$kanto = new PGDB();

		if (!$kanto->ok()) die; // FIXME

		$kysely = sprintf($tarkistuskysely, $user, $passwd);

		$debug->debug("[qry]:: ".$kysely);

		if ($kanto->query($kysely)) {
			$vastaus = $kanto->getline();

			$debug->debug(" :-) :: ".$vastaus["tunnus"]);

			$this->valid_user = true;
			$this->username = $vastaus["tunnus"];

			if ($vastaus["yllapeto"] === true) {
				$debug->debug(" ^_^ :: on jopa admin");

				$this->is_admin = true;
			}
		}
		else {
			$debug->debug(" :-| Strömssö-tapahtuma jäi uupumaan");
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
}

?>
