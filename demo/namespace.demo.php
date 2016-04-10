<?php
require_once 'demo.php';

$daemon = getNamespacedDemoDaemon(require_once 'port.php');
$daemon->run();