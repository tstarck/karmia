<?php

$alku = <<<ALKU
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Karmia > Isohali</title>
<link rel="shortcut icon" type="image/png" href="icon.png" />
<link rel="stylesheet" title="Isohali" href="isohali.css" media="screen" />
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="linkit.js"></script>
<script src="linkit.php"></script>
</head>
<body>
<h1>Karmia</h1>
<ul id="linkit"></ul>
ALKU;

$loppu = <<<LOPPU
</body>
</html>
LOPPU;

class XHTML {
	function __construct() {
		global $alku;
		echo $alku;
	}

	function __destruct() {
		global $loppu;
		echo $loppu;
	}

	public function taulukoi($kuvaus=null, $otsake=null, $data=null) {
		$tr = "<td class=\"%s\">%s</td>\n";

		echo "<table>\n";

		if (!empty($kuvaus)) {
			echo "<caption>", $kuvaus, "</caption>\n";
		}

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

	public function kappale($asia) {
		echo "<p>", $asia, "</p>\n";
	}

	public function linkki($teksti, $href) { // FIXME unused
		$a = "<a href=\"$href\">$teksti</a>\n";
		echo sprintf($a, $href);
		return $this;
	}
}

?>
