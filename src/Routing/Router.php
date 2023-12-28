<?php

namespace App\Routing;

use App\Routing\Attribute\Route as RouteAttribute;
use App\Routing\Exception\RouteNotFoundException;
use App\Utils\Filesystem;
use Psr\Container\ContainerInterface;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    private const CONTROLLERS_BASE_DIR = __DIR__ . "/../Controller/";
    private const CONTROLLERS_NAMESPACE_PREFIX = "App\\Controller\\";

    public function __construct(
        public ContainerInterface $container
    ) {
        $this->registerRoutes();
    }

    public function addRoute(Route $route): self
    {
        foreach ($this->routes as $existingRoute) {
            if ($existingRoute->getUri() === $route->getUri() && $existingRoute->getHttpMethod() === $route->getHttpMethod()) {
                throw new \Exception("Une route identique existe déjà."); // Gérer les doublons
            }
        }

        $this->routes[] = $route;
        return $this;
    }

    public function getRoute(string $uri, string $httpMethod): ?Route
    {
        foreach ($this->routes as $savedRoute) {
            if ($savedRoute->getUri() === $uri && $savedRoute->getHttpMethod() === $httpMethod) {
                return $savedRoute;
            }
        }

        return null;
    }

    public function registerRoutes(): void
    {
    }

    public function execute(string $uri, string $httpMethod): string
    {
        $route = $this->getRoute($uri, $httpMethod);

        if ($route === null) {
            throw new RouteNotFoundException();
        }

        $controllerClass = $route->getControllerClass();
        $constructorParams = $this->getMethodParams($controllerClass . '::__construct');
        $controllerInstance = new $controllerClass(...$constructorParams);

        $method = $route->getController();
        $controllerParams = $this->getMethodParams($controllerClass . '::' . $method);
        return $controllerInstance->$method(...$controllerParams);
    }

    private function getMethodParams(string $method): array
    {
        $methodInfos = new \ReflectionMethod($method);
        $methodParameters = $methodInfos->getParameters();

        $params = [];
        foreach ($methodParameters as $param) {
            $paramType = $param->getType();
            if (!$paramType) {
                continue; // Gérer le cas où le type de paramètre n'est pas défini
            }
            $paramTypeFQCN = $paramType->getName();
            $params[] = $this->container->get($paramTypeFQCN);
        }

        return $params;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
