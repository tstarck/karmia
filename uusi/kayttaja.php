<?php

require_once '../pgdb.php';
require_once '../common.php';
require_once '../config/karmia.php';
require_once '../debug.php';

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
	header("Location: kayttaja.xhtml#".join(",", $error));
	exit;
}

$kanto = new PGDB();

if (!$kanto->ok()) die; // FIXME

if ($kanto->query(sprintf($tietojentalletus, $newuser, sha1($passone)))) {
	// FIXME
}

if (!setcookie("user", $user, 3600+time())) {
	die; // FIXME
}

if (!setcookie("pass", sha1($pass), 3600+time())) {
	die; // FIXME
}

header("Location: ".$__karmia_root);

?>
