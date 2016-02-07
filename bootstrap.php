<?php

error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Silex\Application;

use Lstr\Silex\Config\ConfigServiceProvider;
use Lstr\Postgres\DbMgmt\Service\DbHostManagerService;
use Lstr\Postgres\DbMgmt\Service\DbMgmtServiceProvider;
use Lstr\Postgres\DbMgmt\Service\HodorServiceProvider;

$app = new Application();

// lstr-silex components
$app->register(new ConfigServiceProvider());
$app->register(new DbMgmtServiceProvider());
$app->register(new HodorServiceProvider());

$app['config'] = $app['lstr.config']->load(array(
    __DIR__ . '/config/autoload/*.global.php',
    __DIR__ . '/config/autoload/*.local.php',
));

if (isset($app['config']['debug'])) {
    $app['debug'] = $app['config']['debug'];
}

$app['job-queue'] = $app['hodor']($app['config']['job_queue']['config_file']);
$app['host-manager'] = new DbHostManagerService($app['config']['hosts']);

return $app;
