<?php

require 'auth.php';

$tunnistus = new AUTH();

if (!$tunnistus->ok()) {
	$vihreet = $tunnistus->err_str();
	$goto = "Location: kirjaudu.xhtml"; // zomg, a wild goto appears :-o

	if (!empty($vihreet)) {
		$goto .= "#" . $vihreet;
	}

	header($goto);
	exit;
}

?>
