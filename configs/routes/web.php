<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index']);
    $app->get('/login', [AuthController::class, 'loginView']);
    $app->post('/login', [AuthController::class, 'logIn']);
};
