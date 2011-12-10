<?php

function get_param($id) {
	if (isset($_GET[$id])) return $_GET[$id];
	if (isset($_POST[$id])) return $_POST[$id];
	return false;
}

function get_cookie($id) {
	if (isset ($_COOKIE[$id])) return $_COOKIE[$id];
	return false;
}

?>
