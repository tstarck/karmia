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

	private $annettu_kayttaja;
	private $annettu_kaarme;
	private $annettu_laji;

	public function __construct() {
		if (!with(new AUTH)->yllapeto()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
		}

		$this->moodi = hae_oikea_arvo("moodi", "/^\w+$/");

		$this->annettu_kayttaja = hae_arvo("tunnus");
		$this->annettu_kaarme = hae_numeroarvo("id");
		$this->annettu_laji = hae_arvo("laji");
	}

	private function kayttajan_ylennys() {
		global $_sql_hali_promoa_kayttaja;

		if (!empty($this->annettu_kayttaja)) {
			with(new PGDB)->kysele($_sql_hali_promoa_kayttaja, $this->annettu_kayttaja);
		}
	}

	private function kayttajan_poisto() {
		global $_sql_hali_poista_kayttaja, $_sql_hali_pois_kayt_lainat;

		if (!empty($this->annettu_kayttaja)) {
			$kanto = new PGDB;
			$kanto->kysele($_sql_hali_pois_kayt_lainat, $this->annettu_kayttaja);
			$kanto->kysele($_sql_hali_poista_kayttaja, $this->annettu_kayttaja);
		}
	}

	private function kaarmeen_poisto() {
		global $_sql_hali_poista_kaarme, $_sql_hali_pois_kaar_lainat;

		if (!empty($this->annettu_kaarme)) {
			$kanto = new PGDB;
			$kanto->kysele($_sql_hali_pois_kaar_lainat, $this->annettu_kaarme);
			$kanto->kysele($_sql_hali_poista_kaarme, $this->annettu_kaarme);
		}
	}

	private function lajin_poisto() {
		global $_sql_hali_poista_laji, $_sql_hali_refaktoroi_kaarmeet;

		if (!empty($this->annettu_laji)) {
			$kanto = new PGDB;
			$maski = preg_replace("/[^a-z]/i", "%", $this->annettu_laji);

			$kanto->kysele($_sql_hali_refaktoroi_kaarmeet, $this->annettu_laji);
			$kanto->kysele($_sql_hali_poista_laji, $this->annettu_laji);
		}
	}

	private function promolinkki($eka, $toka) {
		$linkki = "<a href=\"?moodi=promota&%s=%s\">&#8679;</a>";

		if ($eka["yllapeto"] === "f") {
			return array_merge(
				$eka, array("promoa" => sprintf($linkki, $toka, $eka[$toka]))
			);
		}
		else {
			return array_merge($eka, array("tyhja" => ""));
		}
	}

	private function poistolinkki($eka, $toka) {
		$linkki = "<a href=\"?moodi=poista&%s=%s\">&#215;</a>";

		return array_merge(
			$eka, array("poista" => sprintf($linkki, $toka, $eka[$toka]))
		);
	}

	public function duunaa() {
		if ($this->moodi === "promota") {
			$this->kayttajan_ylennys();
		}
		elseif ($this->moodi === "poista") {
			$this->kayttajan_poisto();
			$this->kaarmeen_poisto();
			$this->lajin_poisto();
		}

		return $this;
	}

	public function ja_tulosta() {
		global $_sql_hali_kayttajat, $_sql_hali_kaarmeet, $_sql_hali_lajit;

		$sivu = new XHTML(
			"Karmia > Isohali",
			array("style" => "isohali.css", "script" => "isohali.js")
		);

		$sivu->kappale("<b>I</b>nteraktiivinen <b>S</b>elkärangattomien <b>O</b>tusten <b>Ha</b>llinta<b>li</b>sta");

		$kayttajat= with(new PGDB)->kysele($_sql_hali_kayttajat)->anna_kaikki()->taulukkona();
		$kaarmeet = with(new PGDB)->kysele($_sql_hali_kaarmeet)->anna_kaikki()->taulukkona();
		$lajit    = with(new PGDB)->kysele($_sql_hali_lajit)->anna_kaikki()->taulukkona();

		$sivu->taulukoi(
			"kayttajat",
			array(
				"tunnus" => "Tunnus",
				"yllapeto" => "<abbr title=\"Ylläpitäjä\">Peto?</abbr>",
				"luotu" => "Luontiaika",
				"promoa" => "<abbr title=\"Tee käyttäjästä ylipeto\">&#8679;</abbr>",
				"poista" => "<abbr title=\"Poista käyttäjä\">&#215;</abbr>"
			),
			array_map(
				array($this, "poistolinkki"),
				array_map(
					array($this, "promolinkki"),
					$kayttajat,
					array_fill(0, count($kayttajat), "tunnus")
				),
				array_fill(0, count($kayttajat), "tunnus")
			)
		);

		$sivu->taulukoi(
			"kaarmeet",
			array(
				"id" => "#",
				"nimi" => "Nimi",
				"laji" => "Laji",
				"poista" => "<abbr title=\"Lopeta käärme\">&#215;</abbr>"
			),
			array_map(
				array($this, "poistolinkki"),
				$kaarmeet,
				array_fill(0, count($kaarmeet), "id")
			)
		);

		$sivu->taulukoi(
			"lajit",
			array(
				"id" => "#",
				"laji" => "Laji",
				"latin" => "Latinaksi",
				"alkupera" => "Alkuperä",
				"vari" => "Väri",
				"myrkyllisyys" => "<abbr title=\"Myrkyllisyys\">Myrk.</abbr>",
				"uhanalaisuus" => "<abbr title=\"Uhanalaisuus\">Uhan.</abbr>",
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

?>
