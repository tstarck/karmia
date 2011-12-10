<?php

class DEBUG {
	private $kahva;

	function __construct() {
		$this->kahva = fopen("debug.log", "a");
		if ($this->kahva === false) {
			echo " !!! debug error\n";
			die;
		}
		fwrite($this->kahva, date("\n>>> ymd H:i:s.u\n"));
	}

	function __destruct() {
		fwrite($this->kahva, "<<<\n");
		fclose($this->kahva);
	}

	function debug($msg) {
		fwrite($this->kahva, $msg."\n");
	}
}

?>
