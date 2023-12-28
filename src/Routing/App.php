<?php

namespace App;

use App\Routing\Router;
use App\Routing\Exception\RouteNotFoundException;
use Psr\Container\ContainerInterface;

class App
{
    private Router $router;
    private ContainerInterface $container;

    public function __construct(Router $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function handle()
    {
        // Obtenez l'URI et la méthode HTTP de la requête actuelle
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        try {
            $response = $this->router->execute($uri, $httpMethod);
            echo $response;
        } catch (RouteNotFoundException $e) {
            // Gérer l'exception (par exemple, afficher une page 404)
            http_response_code(404);
            echo "Page non trouvée";
        }
    }
}
