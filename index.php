<?php

require 'auth.php';

$tunnistus = new AUTH();

if (!$tunnistus->ok()) {
	header("Location: login.xhtml");
	exit;
}

?>
