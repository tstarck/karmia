<?php

require_once 'auth.php';

if (with(new AUTH)->ok()) {
	header("Content-Type: application/xhtml+xml; charset=utf-8");
	readfile("karmia.xhtml");
}
else {
	header("Location: kirjaudu.xhtml");
}

exit;

?>
