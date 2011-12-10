<?php

require 'auth.php';

$tunnistus = new AUTH();

if (!$tunnistus->ok()) {
	$vihreet = $tunnistus->err_str();
	$goto = "Location: login.xhtml"; // zomg, a wild goto appears :-o

	if (!empty($vihreet)) {
		$goto .= "#" . $vihreet;
	}

	header($goto);
	exit;
}

?>
