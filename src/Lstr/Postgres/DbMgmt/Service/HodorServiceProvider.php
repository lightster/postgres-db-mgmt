<?php

namespace Lstr\Postgres\DbMgmt\Service;

use Hodor\JobQueue\JobQueue;
use Silex\Application;
use Silex\ServiceProviderInterface;

class HodorServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['hodor'] = $app->protect(function ($config_file) use ($app) {
            return $app->share(function (Application $app) use ($config_file) {
                $job_queue = new JobQueue();
                $job_queue->setConfigFile($config_file);

                return $job_queue;
            });
        });
    }

    public function boot(Application $app)
    {
    }
}
