<?php

require_once 'auth.php';

$tunnistus = new AUTH();

if (!$tunnistus->ok()) {
	header("Location: kirjaudu.xhtml");
	exit;
}

?>
