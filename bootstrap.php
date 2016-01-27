<?php

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Silex\Application;

use Lstr\Silex\Config\ConfigServiceProvider;

$app = new Application();

// lstr-silex components
$app->register(new ConfigServiceProvider());

$app['config'] = $app['lstr.config']->load(array(
    __DIR__ . '/config/autoload/*.global.php',
    __DIR__ . '/config/autoload/*.local.php',
));

if (isset($app['config']['debug'])) {
    $app['debug'] = $app['config']['debug'];
}

return $app;
