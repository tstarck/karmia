<?php

require 'config/karmia.php';
require 'common.php';
require 'debug.php';

$user = get_param("user");
$pass = get_param("pass");

$debug = new DEBUG("login.php");
$debug->debug(sprintf("[u/p]:: %s / %s", $user, $pass));
$debug->debug("hash :: ".sha1($pass));

if (!setcookie("user", $user, 3600+time())) {
	echo "error out\n";
	die; // FIXME
}

if (!setcookie("pass", sha1($pass), 3600+time())) {
	echo "error in\n";
	die; // FIXME
}

header("Location: ".$__karmia_root);

?>
