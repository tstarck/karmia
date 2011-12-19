<?php

require_once 'config/karmia.php';
require_once 'common.php';

poista_pipari("user");
poista_pipari("pass");

header("Location: ".$_karmia_root);

?>
