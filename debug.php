<?php

$debugfilename = "debug.log";

class DEBUG {
	private $kahva;

	function __construct() {
		$this->kahva = @fopen($debugfilename, "a");
		fwrite($this->kahva, date("\n>>> %y%m%d %H:%i:%s.%u\n"));
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
