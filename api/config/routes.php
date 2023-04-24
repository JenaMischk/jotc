<?php

use Slim\App;


return function (App $app) {

    $app->get('/', \App\Action\Home\HomeAction::class);

    $app->post('/login', \App\Action\User\UserLoginAction::class);

};