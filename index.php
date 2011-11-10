<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Karmia</title>
</head>
<body>
<?php

require 'pgkanto.php';

function tulosta($viesti) {
	echo "<p>$viesti</p>\n";
}

$kanto = new PGDB();

if (!$kanto->ok()) die;

$kysely = "SELECT * FROM laji WHERE id = 0";

if ($kanto->query($kysely)) {
	echo "<pre>\n";
	var_dump($kanto->getline());
	echo "</pre>\n";
}
else {
	tulosta(":-(");
}

?>
</body>
</html>
