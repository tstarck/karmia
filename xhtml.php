<?php

$alku = <<<ALKU
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>%s</title>
<link rel="stylesheet" title="Karmia" href="%s" media="screen" />
</head>
<body>
ALKU;

$loppu = <<<LOPPU
</body>
</html>
LOPPU;

class XHTML {
	function __construct($otsake = "Karmia", $tyyli = "main.css") {
		global $alku;
		echo sprintf($alku, $otsake, $tyyli);
	}

	function __destruct() {
		global $loppu;
		echo $loppu;
	}

	public function otsikko($otsikko) {
		echo "<h1>", $otsikko, "</h1>\n";
		return $this;
	}

	public function kappale($teksti) {
		echo "<p>", $teksti, "</p>\n";
		return $this;
	}

	public function linkki($teksti, $href) {
		$a = "<a href=\"$href\">$teksti</a>\n";
		echo sprintf($a, $href);
		return $this;
	}
}

?>
