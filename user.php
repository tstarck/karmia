<?php

require 'pgdb.php';
require 'common.php';
require 'config/karmia.php';
require 'debug.php';

$error = array();
$tietojentalletus = "INSERT INTO kayttajat VALUES ('%s', '%s')";

$newuser = get_param("newuser");
$passone = get_param("passone");
$passtwo = get_param("passtwo");

if (!preg_match("/^[a-z][0-9a-z_]+$/i", $newuser)) {
	array_push($error, "invalid_username");
}

if ($passone !== $passtwo) {
	array_push($error, "password_mismatch");
}

if (!empty($error)) {
	header("Location: user.xhtml#".join(",", $error));
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
