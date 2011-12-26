<?php

require_once 'auth.php';
require_once 'common.php';
require_once 'sql.php';
require_once 'xhtml.php';

/* Interaktiivinen selkärangattomien otusten hallintalista
 */
class ISOHALI {
	private $kanto;
	private $moodi;

	public function __construct() {
		if (!with(new AUTH)->yllapeto()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
		}

		$this->kanto = new PGDB;

		$this->moodi = hae_oikea_arvo("moodi", "/^\w+$/");
	}

	private function kayttajan_ylennys() {
		global $_sql_hali_promoa_kayttaja;

		$kayttaja = hae_arvo("tunnus");

		if (!empty($kayttaja)) {
			$this->kanto->kysele($_sql_hali_promoa_kayttaja, $kayttaja);
		}
	}

	private function kaarmeen_lisays() {
		global $_sql_hali_uusi_kaarme;

		$nimi = hae_arvo("nimi");
		$laji = hae_arvo("lajinro");

		if (!empty($nimi) and ctype_digit($laji)) {
			$this->kanto->kysele($_sql_hali_uusi_kaarme, $nimi, $laji);
		}
	}

	private function lajin_lisays() {
		global $_sql_hali_uusi_laji;

		$laji         = hae_arvo("laji");
		$latin        = hae_arvo("latin");
		$alkupera     = hae_arvo("alkupera");
		$vari         = hae_arvo("vari");
		$myrkyllisyys = hae_arvo("myrkyllisyys");
		$uhanalaisuus = hae_arvo("uhanalaisuus");

		if (empty($laji))  return;
		if (empty($latin)) return;
		if (empty($vari))  return;

		if (!ctype_digit($alkupera)) return;
		if (!ctype_digit($myrkyllisyys)) return;

		if (!preg_match("/^..?$/", $uhanalaisuus)) return;

		$this->kanto->kysele($_sql_hali_uusi_laji,
			$laji, $latin, $alkupera, $vari, $myrkyllisyys, $uhanalaisuus
		);
	}

	private function kayttajan_poisto() {
		global $_sql_hali_poista_kayttaja, $_sql_hali_pois_kayt_lainat;

		$kayttaja = hae_arvo("tunnus");

		if (!empty($kayttaja)) {
			$this->kanto->kysele($_sql_hali_pois_kayt_lainat, $kayttaja);
			$this->kanto->kysele($_sql_hali_poista_kayttaja, $kayttaja);
		}
	}

	private function kaarmeen_poisto() {
		global $_sql_hali_poista_kaarme, $_sql_hali_pois_kaar_lainat;

		$kaarme = hae_numeroarvo("id");

		if (!empty($kaarme)) {
			$this->kanto->kysele($_sql_hali_pois_kaar_lainat, $kaarme);
			$this->kanto->kysele($_sql_hali_poista_kaarme, $kaarme);
		}
	}

	private function lajin_poisto() {
		global $_sql_hali_poista_laji, $_sql_hali_refaktoroi_kaarmeet;

		$laji = hae_arvo("laji");

		if (!empty($laji)) {
			$maski = preg_replace("/[^a-z]/i", "%", $laji);

			$this->kanto->kysele($_sql_hali_refaktoroi_kaarmeet, $laji);
			$this->kanto->kysele($_sql_hali_poista_laji, $laji);
		}
	}

	private function promolinkki($eka, $toka) {
		$linkki = "<a href=\"?moodi=promoa&%s=%s\">&#8679;</a>";

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
		if ($this->moodi === "promoa") {
			$this->kayttajan_ylennys();
		}
		elseif ($this->moodi === "uusi") {
			$this->kaarmeen_lisays();
			$this->lajin_lisays();
		}
		elseif ($this->moodi === "poista") {
			$this->kayttajan_poisto();
			$this->kaarmeen_poisto();
			$this->lajin_poisto();
		}

		return $this;
	}

	public function ja_tulosta() {
		global $_sql_hali_kayttajat, $_sql_hali_kaarmeet, $_sql_hali_lajit, $_sql_hali_alkuperat;

		$kayttajat = $this->kanto->kysele($_sql_hali_kayttajat)->anna_kaikki()->taulukkona();
		$kaarmeet  = $this->kanto->kysele($_sql_hali_kaarmeet)->anna_kaikki()->taulukkona();
		$lajit     = $this->kanto->kysele($_sql_hali_lajit)->anna_kaikki()->taulukkona();
		$alkuperat = $this->kanto->kysele($_sql_hali_alkuperat)->anna_kaikki()->taulukkona();

		$sivu = new XHTML(
			"Karmia > Isohali",
			array("css" => "isohali.css", "js" => "isohali.js")
		);

		$sivu->kappale("<b>I</b>nteraktiivinen <b>S</b>elkärangattomien <b>O</b>tusten <b>Ha</b>llinta<b>li</b>sta");

		$sivu->taulukoi(
			"kayttajat",
			array(
				"tunnus" => "<abbr title=\"Selkärangaton otu.. tarkoitan siis käyttäjä!\">Tunnus</abbr>",
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
				"id" => "",
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

		$sivu->lomake(
			"kaarme", $_SERVER["SCRIPT_NAME"], array(
				array("lbl", "nimi", "Lisää käärme:"),
				array("inp", "nimi", "Nimi"),
				array("sel", "lajinro", array("id", "laji", $lajit)),
				array("hid", "moodi", "uusi"),
				array("sub", "Lisää")
			)
		);

		$sivu->taulukoi(
			"lajit",
			array(
				"id" => "",
				"laji" => "Laji",
				"latin" => "Latinaksi",
				"alkupera" => "Alkuperä",
				"vari" => "Väri / ulkonäkö",
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

		$myrkyllisyydet = array(
			array("n" => 0, "m" => "ei tiedossa"),
			array("n" => 1, "m" => "ei myrkyllinen"),
			array("n" => 2, "m" => "myrkyllinen"),
			array("n" => 3, "m" => "tappavan myrkyllinen")
		);

		$uhanalaisuudet = array(
			array("n" => "-", "m" => "-"),
			array("n" => "LC", "m" => "LC"),
			array("n" => "NT", "m" => "NT"),
			array("n" => "VU", "m" => "VU"),
			array("n" => "EN", "m" => "EN"),
			array("n" => "CR", "m" => "CR"),
			array("n" => "EW", "m" => "EW"),
			array("n" => "EX", "m" => "EX"),
			array("n" => "DD", "m" => "DD"),
			array("n" => "NE", "m" => "NE")
		);

		$sivu->lomake(
			"laji", $_SERVER["SCRIPT_NAME"], array(
				array("lbl", "laji", "Lisää uusi laji:"),
				array("inp", "laji", "Lajin nimi"),
				array("inp", "latin", "Latinankielinen nimi"),
				array("sel", "alkupera", array("id", "alkupera", $alkuperat)),
				array("inp", "vari", "Väri / ulkonäkö"),
				array("sel", "myrkyllisyys", array("n", "m", $myrkyllisyydet)),
				array("sel", "uhanalaisuus", array("n", "m", $uhanalaisuudet)),
				array("hid", "moodi", "uusi"),
				array("sub", "Lisää")
			)
		);
	}
}

with(new ISOHALI)->duunaa()->ja_tulosta();

?>
