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
	"script" => "<script src=\"%s\"></script>\n",
	"style"  => "<link rel=\"stylesheet\" href=\"%s\" media=\"screen\" />\n"
);

$loppu = <<<LOPPU
</body>
</html>
LOPPU;

class XHTML {
	function __construct($otsikko="Karmia", $otsakkeet=null) {
		global $alku, $elementit;

		$raw = "";

		if (!empty($otsakkeet)) {
			foreach ($otsakkeet as $avain => $arvo) {
				$raw .= sprintf($elementit[$avain], $arvo);
			}
		}

		echo sprintf($alku, $otsikko, $raw);
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
}

?>
