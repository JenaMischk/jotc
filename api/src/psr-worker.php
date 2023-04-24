<?php

declare(strict_types=1);

include 'vendor/autoload.php';

// Instantiate DI container with Roadrunner support
$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions('config/container.php');
$container = $containerBuilder->build();

// Instantiate Slim app
$app = DI\Bridge\Slim\Bridge::create($container);
//Slim\Factory\AppFactory::setContainer($container);
//$app = Slim\Factory\AppFactory::create();

// Register routes
(require './config/routes.php')($app);

// Register middleware
(require './config/middleware.php')($app);


$worker = $app->getContainer()->get(\Spiral\RoadRunner\Http\PSR7WorkerInterface::class);

while(true){

    try {

        $req = $worker->waitRequest();
        $res = $app->handle($req);
    
        if (!($req instanceof \Psr\Http\Message\ServerRequestInterface)) { // Termination request received
            break;
        }

    } catch (\Throwable) {
        $worker->respond(new Nyholm\Psr7\Response(400)); // Bad Request
        continue;
    }

    try {
        $worker->respond($res);
    } catch (\Throwable) {
        $worker->respond(new Nyholm\Psr7\Response(500, [], 'Something Went Wrong!'));
    }

}