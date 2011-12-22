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

	public function __construct() {
		if (!with(new AUTH)->yllapeto()) {
			header("HTTP/1.1 401 Unauthorized");
			die("401 Unauthorized");
		}

		$this->moodi = hae_oikea_arvo("moodi", "/^\w+$/");
	}

	private function kayttajan_ylennys() {
		global $_sql_hali_promoa_kayttaja;

		$kayttaja = hae_arvo("tunnus");

		if (!empty($kayttaja)) {
			with(new PGDB)->kysele($_sql_hali_promoa_kayttaja, $kayttaja);
		}
	}

	private function kaarmeen_lisays() {
		/*
			[nimi] => Hermanni
			[laji] => 3
		*/
	}

	private function lajin_lisays() {
	}

	private function kayttajan_poisto() {
		global $_sql_hali_poista_kayttaja, $_sql_hali_pois_kayt_lainat;

		$kayttaja = hae_arvo("tunnus");

		if (!empty($kayttaja)) {
			$kanto = new PGDB;
			$kanto->kysele($_sql_hali_pois_kayt_lainat, $kayttaja);
			$kanto->kysele($_sql_hali_poista_kayttaja, $kayttaja);
		}
	}

	private function kaarmeen_poisto() {
		global $_sql_hali_poista_kaarme, $_sql_hali_pois_kaar_lainat;

		$kaarme = hae_numeroarvo("id");

		if (!empty($kaarme)) {
			$kanto = new PGDB;
			$kanto->kysele($_sql_hali_pois_kaar_lainat, $kaarme);
			$kanto->kysele($_sql_hali_poista_kaarme, $kaarme);
		}
	}

	private function lajin_poisto() {
		global $_sql_hali_poista_laji, $_sql_hali_refaktoroi_kaarmeet;

		$laji = hae_arvo("laji");

		if (!empty($laji)) {
			$kanto = new PGDB;
			$maski = preg_replace("/[^a-z]/i", "%", $laji);

			$kanto->kysele($_sql_hali_refaktoroi_kaarmeet, $laji);
			$kanto->kysele($_sql_hali_poista_laji, $laji);
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

		$kanto = new PGDB;

		$kayttajat = $kanto->kysele($_sql_hali_kayttajat)->anna_kaikki()->taulukkona();
		$kaarmeet  = $kanto->kysele($_sql_hali_kaarmeet)->anna_kaikki()->taulukkona();
		$lajit     = $kanto->kysele($_sql_hali_lajit)->anna_kaikki()->taulukkona();
		$alkuperat = $kanto->kysele($_sql_hali_alkuperat)->anna_kaikki()->taulukkona();

		$sivu = new XHTML(
			"Karmia > Isohali",
			array("css" => "isohali.css", "js" => "isohali.js")
		);

		echo "<!-- kusti polkee: ";
		print_r($_POST);
		echo "-->\n";

		$sivu->kappale("<b>I</b>nteraktiivinen <b>S</b>elkärangattomien <b>O</b>tusten <b>Ha</b>llinta<b>li</b>sta");

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

		$sivu->lomake(
			"kaarme", $_SERVER["SCRIPT_NAME"], array(
				array("lbl", "nimi", "Lisää käärme:"),
				array("inp", "nimi"),
				array("sel", "laji", array("id", "laji", $lajit)),
				array("hid", "moodi", "uusi"),
				array("sub", "Lisää")
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

		$myrkyllisyydet = array(
			array("n" => 0, "m" => "ei tiedossa"),
			array("n" => 1, "m" => "ei myrkyllinen"),
			array("n" => 2, "m" => "myrkyllinen"),
			array("n" => 3, "m" => "tappavan myrkyllinen")
		);

		$sivu->lomake(
			"laji", $_SERVER["SCRIPT_NAME"], array(
				array("lbl", "laji", "Lisä uusi laji:"),
				array("inp", "laji"),
				array("inp", "latin"),
				array("sel", "alkupera", array("id", "alkupera", $alkuperat)),
				array("inp", "vari"),
				array("sel", "myrkyllisyys", array("n", "m", $myrkyllisyydet)),
				array("inp", "uhanalaisuus"),
				array("hid", "moodi", "uusi"),
				array("sub", "Lisää")
			)
		);
	}
}

with(new ISOHALI)->duunaa()->ja_tulosta();

?>
