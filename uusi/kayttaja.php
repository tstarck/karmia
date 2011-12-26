<?php

require_once '../config/karmia.php';
require_once '../common.php';
require_once '../pgdb.php';
require_once '../sql.php';

$error = array();

$newuser = hae_arvo("newuser");
$ulength = strlen($newuser);
$passone = hae_arvo("passone");
$passtwo = hae_arvo("passtwo");

if ($ulength < 1 or $ulength > 8 or
	!preg_match("/^[a-z][0-9a-z_]+$/i", $newuser)) {
	array_push($error, "huono_tunnus");
}

if ($passone !== $passtwo) {
	array_push($error, "sovittamattomat_salasanat");
}

if (!empty($error)) {
	$str = (empty($error))? "": "#".join(",", $error);
	header("Location: kayttaja.xhtml".$str);
	exit;
}

with(new PGDB)->kysele($_sql_uusi_kayttaja, $newuser, sha1($passone));

aseta_pipari("user", $newuser);
aseta_pipari("pass", sha1($passone));

header("Location: ".$_karmia_root);

?>
