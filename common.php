<?php

function get_cookie($id) {
	if (isset ($_COOKIE[$id])) return $_COOKIE[$id];
	return false;
}

?>
