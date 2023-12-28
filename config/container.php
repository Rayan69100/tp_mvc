<?php

use App\DependencyInjection\Container;
use App\Model\User;
use App\Service\SessionManager;
use App\Controller\AuthController;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();


$container->set(User::class, function () use ($container) {
    $dbConnection = $container->get('db');
    return new User($dbConnection);
});



$container->set(SessionManager::class, function () {
    return new SessionManager();
});


$container->set(AuthController::class, function ($container) {
    return new AuthController(
        $container->get(User::class),
        $container->get(SessionManager::class)
    );
});

return $container;
