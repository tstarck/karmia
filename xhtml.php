<?php

$alku = <<<ALKU
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>%s</title>
<link rel="shortcut icon" type="image/png" href="icon.png" />
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="linkit.js"></script>
<script src="linkit.php"></script>
%s</head>
<body>
<h1>Karmia</h1>
<ul id="linkit"></ul>
ALKU;

$elementit = array(
	"js" => "<script src=\"%s\"></script>\n",
	"css"  => "<link rel=\"stylesheet\" href=\"%s\" media=\"screen\" />\n",
	"frm" => "<form id=\"%1\$s\" name=\"%1\$s\" method=\"post\" action=\"%2\$s\">\n",
	"lbl" => "<label for=\"%s\">%s</label><br />\n",
	"inp" => "<input type=\"text\" id=\"%1\$s\" name=\"%1\$s\" /><br />\n",
	"hid" => "<input type=\"hidden\" id=\"%1\$s\" name=\"%1\$s\" value=\"%2\$s\" />\n",
	"sel" => "<select id=\"%1\$s\" name=\"%1\$s\">\n",
	"opt" => "<option value=\"%s\">%s</option>\n",
	"sub" => "<input type=\"submit\" value=\"%s\" />\n"
);

$loppu = <<<LOPPU
</body>
</html>
LOPPU;

class XHTML {
	function __construct($otsikko="Karmia", $otsakkeet=null) {
		global $alku, $elementit;

		$buf = "";

		if (!empty($otsakkeet)) {
			foreach ($otsakkeet as $avain => $arvo) {
				$buf .= sprintf($elementit[$avain], $arvo);
			}
		}

		echo sprintf($alku, $otsikko, $buf);
	}

	function __destruct() {
		global $loppu;
		echo $loppu;
	}

	public function taulukoi($id, $otsake=null, $data=null) {
		$tr = "<td class=\"%s\">%s</td>\n";

		echo sprintf("<table id=\"%s\">\n", $id);

		if (!empty($otsake)) {
			echo "<thead>\n";
			echo "<tr>\n";

			foreach ($otsake as $luokka => $solu) {
				echo sprintf($tr, $luokka, $solu);
			}

			echo "</tr>\n";
			echo "</thead>\n";
		}

		if (!empty($data)) {
			echo "<tbody>\n";

			foreach ($data as $rivi) {
				echo "<tr>\n";
				foreach ($rivi as $luokka => $solu) {
					echo sprintf($tr, $luokka, $solu);
				}
				echo "</tr>\n";
			}

			echo "</tbody>\n";
		}

		echo "</table>\n";
	}

	public function kappale() {
		echo "<p>", join(func_get_args()), "</p>\n";
	}

	private function vaihtoehdot($data) {
		global $elementit;

		$buf    = "";
		$arvo   = array_shift($data);
		$nimike = array_shift($data);

		foreach ($data[0] as $tapaus) {
			$buf .= sprintf($elementit["opt"], $tapaus[$arvo], $tapaus[$nimike]);
		}

		return $buf;
	}

	private function mprintf($e, $data) { /* My print format */
		global $elementit;

		$muoto = $elementit[$e];

		if (is_string($data)) return sprintf($muoto, $data);

		if (is_array($data)) {
			if (count($data) == 1) return sprintf($muoto, $data[0]);
			if (count($data) == 2) return sprintf($muoto, $data[0], $data[1]);
			if (count($data) == 3) return sprintf($muoto, $data[0], $data[1], $data[2]);
			if (count($data) == 4) return sprintf($muoto, $data[0], $data[1], $data[2], $data[3]);
		}

		return "";
	}

	private function sisus($data) {
		$e = array_shift($data);

		if ($e === "sel") {
			$nimi = array_shift($data);
			$opts = array_shift($data);

			echo $this->mprintf($e, $nimi), $this->vaihtoehdot($opts), "</select><br />\n";
		}
		else {
			echo $this->mprintf($e, $data);
		}
	}

	public function lomake($nimi, $kohde, $sisus) {
		echo $this->mprintf("frm", array($nimi, $kohde));

		foreach ($sisus as $data) {
			$this->sisus($data);
		}

		echo "</form>\n";
	}
}

?>
