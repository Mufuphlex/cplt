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