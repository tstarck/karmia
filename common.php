<?php

require_once 'config/karmia.php';

function hae_arvo($id) {
	if (isset($_GET[$id])) return $_GET[$id];
	if (isset($_POST[$id])) return $_POST[$id];
	return false;
}

function hae_oikea_arvo($id, $re) {
	$arvo = hae_arvo($id);
	if (preg_match($re, $arvo)) return $arvo;
	return false;
}

function hae_pipari($id) {
	if (isset ($_COOKIE[$id])) return $_COOKIE[$id];
	return false;
}

function aseta_pipari($avain, $arvo) {
	global $__karmia_root;
	if (!setcookie($avain, $arvo, 3600+time(), $__karmia_root)) {
		error_log("Piparin laitto ei onnistunut");
	}
}

function with($olio) { /* Python-o-matic */ return $olio; }

?>
