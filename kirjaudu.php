<?php

require_once 'config/karmia.php';
require_once 'auth.php';
require_once 'common.php';

$user = get_param("user");
$pass = get_param("pass");

if (empty($user) or empty($pass)) {
	if (with(new AUTH)->ok()) {
		header("Location: ".$__karmia_root);
	}
	else {
		header("Location: kirjaudu.xhtml#virheellinen_kirjautuminen");
	}

	exit;
}

aseta_pipari("user", $user);
aseta_pipari("pass", sha1($pass));

header("Location: ".$_SERVER["SCRIPT_NAME"]);

?>
