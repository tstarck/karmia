<?php

require_once '../pgdb.php';
require_once '../common.php';
require_once '../config/karmia.php';

$error = array();
$tietojentalletus = "INSERT INTO kayttajat VALUES ('%s', '%s')";

$newuser = get_param("newuser");
$ulength = strlen($newuser);
$passone = get_param("passone");
$passtwo = get_param("passtwo");

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

with(new PGDB)->kysele(sprintf($tietojentalletus, $newuser, sha1($passone))); // FIXME virheet?

aseta_pipari("user", $newuser);
aseta_pipari("pass", sha1($passone));

header("Location: ".$__karmia_root);

?>
