<?php

require_once 'auth.php';
require_once 'common.php';
require_once 'xhtml.php';

$kayttajakysely = <<<KAYTTAJAT
SELECT tunnus, yllapeto, luotu FROM kayttajat
KAYTTAJAT;

$kaarmekysely = <<<KAARMEET
SELECT id, nimi, laji FROM kaarmeet
KAARMEET;

$lajikysely = <<<LAJIT
SELECT id, laji, latin, alkupera, vari, myrkyllisyys, uhanalaisuus FROM lajit
LAJIT;

/* Interaktiivinen selkärangattomien otusten hallintalista
 * ^               ^                 ^       ^^      ^^
 */
class ISOHALI {
	private $sivu;

	function __construct() {
		$this->sivu = null;

		if (!with(new AUTH)->yllapeto()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
			exit;
		}
	}

	function duunaa() {
		return $this;
	}

	function ja_tulosta() {
		global $kayttajakysely, $kaarmekysely, $lajikysely;

		$sivu = new XHTML;
		$sivu->kappale("Interaktiivinen Selkärangattomien Otusten Hallintalista");

		$kayttajat= with(new PGDB)->kysele($kayttajakysely)->anna_kaikki()->taulukkona();
		$kaarmeet = with(new PGDB)->kysele($kaarmekysely)->anna_kaikki()->taulukkona();
		$lajit    = with(new PGDB)->kysele($lajikysely)->anna_kaikki()->taulukkona();

		$sivu->taulukoi(
			"Palvelun käyttäjät.",
			array( "tunnus" => "Tunnus", "yllapeto" => "<abbr title=\"Ylläpitäjä\">Peto?</abbr>", "luotu" => "Luontiaika"),
			array_map($kayttajat)
		);

		$sivu->taulukoi(
			"Ihan simona matoja.",
			array( "id" => "#", "nimi" => "Nimi", "laji" => "Laji"),
			$kaarmeet
		);

		$sivu->taulukoi(
			"Lajit. Piste.",
			array(
				"id" => "#",
				"laji" => "Laji",
				"latin" => "Latinaksi",
				"alkupera" => "Alkupera",
				"vari" => "Väri",
				"myrkyllisyys" => "Myrkyllisyys",
				"uhanalaisuus" => "Uhanalaisuus"
			),
			$lajit
		);
	}
}

with(new ISOHALI)->duunaa()->ja_tulosta();

/*
   – lisää käärme (ylläpeto)
   – poista käärme (ylläpeto)

   – lisää laji (ylläpeto)
   – poista laji (ylläpeto)

   – poista käyttäjä (ylläpeto)

   – käärmeyksilön lainahistorian tulostus (ylläpeto)
*/

?>
