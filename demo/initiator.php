<?php
require_once '../vendor/autoload.php';

$port = require_once 'port.php';

$port = isset($argv[1]) ? $argv[1] : $port;

$text = file_get_contents('./data/where-love-is-there-god-is-also.txt');

$cplt = \Mufuphlex\Cplt\Factory::makeDemo($text, $port);
echo "\nMemory usage (current/peak), Kb: ".memory_get_usage(true).' / '.memory_get_peak_usage(true)."\n";
$cplt->run();