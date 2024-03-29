<?php

require_once 'config/karmia.php';
require_once 'auth.php';
require_once 'common.php';

class KIRJAUDU {
	function __construct() {
		global $_karmia_root;

		if (with(new AUTH)->ok()) {
			header("Location: ".$_karmia_root);
			exit;
		}

		$user = hae_arvo("user");
		$pass = hae_arvo("pass");

		if (empty($user) or empty($pass)) {
			header("Location: kirjaudu.xhtml#virheellinen_kirjautuminen");
			exit;
		}

		aseta_pipari("user", $user);
		aseta_pipari("pass", sha1($pass));

		header("Location: ".$_SERVER["SCRIPT_NAME"]);
	}
}

new KIRJAUDU;

?>
