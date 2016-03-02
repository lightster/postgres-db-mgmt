<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DbMgmtServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['postgres.db-mgmt-job'] = $app->share(function (Application $app) {
            return new DbMgmtJobService($app['postgres.db-mgmt-process'], $app['job-queue']);
        });
        $app['postgres.db-mgmt-process'] = $app->share(function () use ($app) {
            return new DbMgmtProcessService($app['host-manager'], $app['dump-path-manager']);
        });
    }

    public function boot(Application $app)
    {
    }
}
