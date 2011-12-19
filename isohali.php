<?php

require_once 'auth.php';
require_once 'common.php';
require_once 'sql.php';
require_once 'xhtml.php';

/* Interaktiivinen selkärangattomien otusten hallintalista
 * ^               ^                 ^       ^^      ^^
 */
class ISOHALI {
	private $moodi;

	private $kayttaja_pois;
	private $kaarme_pois;
	private $laji_pois;

	public function __construct() {
		if (!with(new AUTH)->yllapeto()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
			exit;
		}

		$this->moodi = hae_oikea_arvo("moodi", "/^\w+$/");

		$this->kayttaja_pois = hae_arvo("tunnus");
		$this->kaarme_pois = hae_numeroarvo("id");
		$this->laji_pois = hae_arvo("laji");
	}

	private function debug($msg) { echo "<!-- ", $msg, " -->\n"; }

	private function kayttajan_poisto() {
		global $_sql_hali_poista_kayttaja;

		if (!empty($this->kayttaja_pois)) {
			with(new PGDB)->kysele($_sql_hali_poista_kayttaja, $this->kayttaja_pois);
		}
	}

	private function kaarmeen_poisto() {
		global $_sql_hali_poista_kaarme;

		if (!empty($this->kaarme_pois)) {
			with(new PGDB)->kysele($_sql_hali_poista_kaarme, $this->kaarme_pois);
		}
	}

	private function lajin_poisto() {
		global $_sql_hali_poista_laji;

		if (!empty($this->laji_pois)) {
			with(new PGDB)->kysele($_sql_hali_poista_laji, $this->laji_pois);
		}
	}

	public function duunaa() {
		if ($this->moodi === "poista") {

			$this->kayttajan_poisto();
			$this->kaarmeen_poisto();
			$this->lajin_poisto();
		}

		return $this;
	}

	private function poistolinkki($foo, $bar) {
		$a = "<a href=\"?moodi=poista&%s=%s\">&#215;</a>";

		return array_merge(
			$foo,
			array("poista" => sprintf($a, $bar, $foo[$bar]))
		);
	}

	public function ja_tulosta() {
		global $_sql_hali_kayttajat, $_sql_hali_kaarmeet, $_sql_hali_lajit;

		$sivu = new XHTML;
		$sivu->kappale("Interaktiivinen Selkärangattomien Otusten Hallintalista");

		$kayttajat= with(new PGDB)->kysele($_sql_hali_kayttajat)->anna_kaikki()->taulukkona();
		$kaarmeet = with(new PGDB)->kysele($_sql_hali_kaarmeet)->anna_kaikki()->taulukkona();
		$lajit    = with(new PGDB)->kysele($_sql_hali_lajit)->anna_kaikki()->taulukkona();

		$sivu->taulukoi(
			"Palvelun käyttäjät.",
			array(
				"tunnus" => "Tunnus",
				"yllapeto" => "<abbr title=\"Ylläpitäjä\">Peto?</abbr>",
				"luotu" => "Luontiaika",
				"poista" => "<abbr title=\"Poista käyttäjä\">&#10013;</abbr>"
			),
			array_map(
				array($this, "poistolinkki"),
				$kayttajat,
				array_fill(0, count($kayttajat), "tunnus")
			)
		);

		$sivu->taulukoi(
			"Ihan simona matoja.",
			array(
				"id" => "#",
				"nimi" => "Nimi",
				"laji" => "Laji",
				"poista" => "<abbr title=\"Lopeta käärme\">&#10013;</abbr>"
			),
			array_map(
				array($this, "poistolinkki"),
				$kaarmeet,
				array_fill(0, count($kaarmeet), "id")
			)
		);

		$sivu->taulukoi(
			"Lajit. Piste.",
			array(
				"id" => "#",
				"laji" => "Laji",
				"latin" => "Latinaksi",
				"alkupera" => "Alkupera",
				"vari" => "Väri",
				"myrkyllisyys" => "<abbr title=\"Myrkyllisyys\">Myrk.</abbr>",
				"uhanalaisuus" => "<abbr title=\"Uhanalaisuus\">Uhka</abbr>",
				"poista" => "<abbr title=\"Poista laji\">&#215;</abbr>"
			),
			array_map(
				array($this, "poistolinkki"),
				$lajit,
				array_fill(0, count($lajit), "laji")
			)
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
