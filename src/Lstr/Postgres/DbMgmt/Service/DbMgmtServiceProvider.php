<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;
use Silex\Application;
use Silex\ServiceProviderInterface;

class DbMgmtServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['postgres.db-mgmt-job'] = $app->share(function () use ($app) {
            return new DbMgmtJobService(new \stdclass, $app['job-queue']);
        });
    }

    public function boot(Application $app)
    {
    }
}
