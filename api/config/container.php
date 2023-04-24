<?php

$psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();

return [

    'settings' => function () {
        return require __DIR__ . '/settings.php';
    },

    Psr\Http\Message\ResponseFactoryInterface::class => $psr17Factory,

    Psr\Http\Message\ServerRequestFactoryInterface::class => $psr17Factory,

    Psr\Http\Message\StreamFactoryInterface::class => $psr17Factory,

    Psr\Http\Message\UploadedFileFactoryInterface::class => $psr17Factory,

    Spiral\RoadRunner\WorkerInterface::class => Spiral\RoadRunner\Worker::create(),

    Spiral\RoadRunner\Http\PSR7WorkerInterface::class => DI\autowire(Spiral\RoadRunner\Http\PSR7Worker::class),

    App\Persistency\DatabaseService::class => function (Psr\Container\ContainerInterface $container) {
        return new App\Persistency\DatabaseService($container->get('settings')['db']);
    },

    App\Persistency\CacheService::class => function (Psr\Container\ContainerInterface $container) {
        return new App\Persistency\CacheService($container->get('settings')['redis']);
    },

];