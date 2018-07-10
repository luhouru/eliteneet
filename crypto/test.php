<?php
require_once('/var/www/includes/report.php');
$data = array('IP Address' => $_SERVER['REMOTE_ADDR']);
report(ERROR, 4, 1, 3, 6, $data, null, __FILE__, __LINE__);
?>