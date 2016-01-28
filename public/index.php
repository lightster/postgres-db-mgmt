<?php

$app = require_once __DIR__ . '/../bootstrap.php';

use Lstr\Postgres\DbMgmt\Controller\DbMgmtControllerProvider;

$app->mount('/api/database', new DbMgmtControllerProvider());

$app->run();
