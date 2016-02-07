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
            return new DbMgmtJobService($app['host-manager'], $app['job-queue']);
        });
    }

    public function boot(Application $app)
    {
    }
}
