<?php

define('ROOT', __DIR__);
define('RUNTIME', ROOT . '/runtime');

require_once ROOT . '/vendor/autoload.php';

$f3 = Base::instance();

$f3->config([
    ROOT . '/cfg/system.ini',
    ROOT . '/cfg/local.ini',
]);

$f3->mset([
    'AUTOLOAD' => ROOT . '/src/',
    'LOCALES' => ROOT . '/dict/',
    'LOGS' => ROOT . '/runtime/logs/',
    'UI' => ROOT . '/tpl/',
    'UPLOADS' => ROOT . '/runtime/uploads/',
]);

$f3->set('LOGGER', new Log(date('Y-m-d.\l\o\g')));
