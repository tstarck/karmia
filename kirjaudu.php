<?php

require_once 'config/karmia.php';
require_once 'auth.php';
require_once 'common.php';
require_once 'debug.php';

$user = get_param("user");
$pass = get_param("pass");

$debug = new DEBUG("kirjaudu.php");
$debug->debug(sprintf("[u/p]:: %s / %s", $user, $pass));
$debug->debug("hash :: ".sha1($pass));

if (empty($user) or empty($pass)) {
	$tunnistus = new AUTH();

	if ($tunnistus->ok()) {
		header("Location: ".$__karmia_root);
	}
	else {
		header("Location: kirjaudu.xhtml#".$tunnistus->err_str());
	}

	exit;
}

aseta_pipari("user", $user);
aseta_pipari("pass", sha1($pass));

header("Location: ".$_SERVER["SCRIPT_NAME"]);

?>