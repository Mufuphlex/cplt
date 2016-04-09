<?php
function getPort(array $argv)
{
    if (!isset($argv[1]) || !is_numeric($argv[1])) {
        return require_once 'port.php';
    }

    return $argv[1];
}

function getFactoryMethod(array $argv)
{
    $method = 'makeDemo';

    if (in_array('--cache', $argv)) {
        $method .= 'Cached';
    }

    return $method;
}

require_once '../vendor/autoload.php';

$port = getPort($argv);
$factoryMethod = getFactoryMethod($argv);

$text = file_get_contents('./data/where-love-is-there-god-is-also.txt');
//$text = file_get_contents('./data/war-and-peace.txt');

$cplt = \Mufuphlex\Cplt\Factory::makeDemo($text, $port);
echo "\nMemory usage (current/peak), Kb: ".memory_get_usage(true).' / '.memory_get_peak_usage(true)."\n";
$cplt->run();