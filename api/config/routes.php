<?php

use Slim\App;


return function (App $app) {

    $app->options('/{routes:.*}', function ($request, $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', \App\Action\Home\HomeAction::class);

    $app->post('/login', \App\Action\User\UserLoginAction::class);

};