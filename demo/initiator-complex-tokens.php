<?php
require_once 'demo.php';

$tokenizer = new \Mufuphlex\Textonic\Tokenizer\TokenizerAsIs();
$container = new \Mufuphlex\Cplt\MultiTokenContainer();
$tokens = array(
    'Alexander Pushkin',
    'Alexander Duma',
    'Lev Tolstoy',
    'Alexey Tolstoy',
    'Mikhail Lermontov',
    'Nicolai Gogol',
    'Donald Knuth',
    'Afanasiy Nikitin',
    'Nikita Groshin',
    'Maxim Levitskiy',
);
$tokensToAdd = array();

foreach ($tokens as $token) {
    $tokensToAdd[] = current($tokenizer->tokenize($token));
}

foreach ($tokensToAdd as $token) {
    $container->addToken($token);
}

$daemon = getContainerDaemon($container, require_once 'port.php');
$daemon->run();