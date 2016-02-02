<?php

namespace Lstr\Postgres\DbMgmt\Controller;

use Exception;

use Lstr\Silex\Controller\JsonRequestMiddlewareService;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DbMgmtControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/dump/{id}',
            function (Application $app, Request $request) {
                return $app->json([]);
            }
        );

        $controllers->post(
            '/dump',
            function (Application $app, Request $request) {
                try {
                    //TODO: validate params
                    $job = $app['postgres.db-mgmt']->dump(
                        $request->get('host'),
                        $request->get('database'),
                        $request->get('destinationPath'),
                        $request->get('callbackUrl')
                    );

                    return $app->json($job);
                } catch (Exception $exception) {
                    return $app->json($exception->getMessage());
                }
            }
        );

        $controllers->get(
            '/restore/{id}',
            function (Application $app, Request $request, $id) {
                return $app->json([]);
            }
        );

        $controllers->post(
            '/restore',
            function (Application $app, Request $request) {
                return $app->json([]);
            }
        );

        $controllers->get(
            '/copy/{id}',
            function (Application $app, Request $request) {
                return $app->json([]);
            }
        );

        $controllers->post(
            '/copy',
            function (Application $app, Request $request) {
                return $app->json([]);
            }
        );

        $controllers->before(new JsonRequestMiddlewareService());

        return $controllers;
    }
}
