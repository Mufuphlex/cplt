<?php
require_once '../vendor/autoload.php';
require_once 'demo.php';

$factoryMethod = getFactoryMethod($argv);

$text = file_get_contents('./data/where-love-is-there-god-is-also.txt');
//$text = file_get_contents('./data/war-and-peace.txt');

$cplt = \Mufuphlex\Cplt\Factory::$factoryMethod($text, getPort($argv));
echo "\nMemory usage (current/peak), Kb: ".memory_get_usage(true).' / '.memory_get_peak_usage(true)."\n";
$cplt->run();