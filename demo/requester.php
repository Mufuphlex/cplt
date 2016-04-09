<?php
require_once '../vendor/autoload.php';

if ($argc <= 1) {
    throw new \InvalidArgumentException('Empty arg');
}
if (!$termNorm = $argv[1]) {
    throw new \InvalidArgumentException('Empty term');
}

require_once '../vendor/autoload.php';

$port = require_once 'port.php';

$socketSender = new \Mufuphlex\Sake\SocketRequester('127.0.0.1', $port);
$result = $socketSender->request($termNorm);

if (!$result = json_decode($result, true)) {
    throw new \Exception('Could not json_decode response');
}

echo "\n";var_dump($result);echo "\n";