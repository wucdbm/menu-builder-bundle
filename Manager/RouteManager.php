<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Manager;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterRepository;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterTypeRepository;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteRepository;

class RouteManager extends Manager {

    /**
     * @var RouteRepository
     */
    protected $routeRepo;

    /**
     * @var RouteParameterRepository
     */
    protected $routeParameterRepo;

    /**
     * @var RouteParameterTypeRepository
     */
    protected $routeParameterTypeRepo;

    /**
     * RouteManager constructor.
     * @param RouteRepository $routeRepo
     * @param RouteParameterRepository $routeParameterRepo
     * @param RouteParameterTypeRepository $routeParameterTypeRepo
     */
    public function __construct(RouteRepository $routeRepo, RouteParameterRepository $routeParameterRepo, RouteParameterTypeRepository $routeParameterTypeRepo) {
        $this->routeRepo = $routeRepo;
        $this->routeParameterRepo = $routeParameterRepo;
        $this->routeParameterTypeRepo = $routeParameterTypeRepo;
    }

    /**
     * @return Route[]
     */
    public function findAll() {
        return $this->routeRepo->findAll();
    }

    /**
     * @param Router $router
     */
    public function importRouter(Router $router) {
        $routes = $this->findAll();

        $toRemove = [];
        /** @var Route $route */
        foreach ($routes as $route) {
            $toRemove[$route->getRoute()] = $route;
        }

        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();

        $allRoutes = $collection->all();

        /**
         * @var string $routeName
         * @var SymfonyRoute $route
         */
        foreach ($allRoutes as $routeName => $route) {
            $this->importRoute($routeName, $route);
            unset($toRemove[$routeName]);
        }

        /**
         * @var string $routeName
         * @var Route $route
         */
        foreach ($toRemove as $routeName => $route) {
            $this->removeRoute($route);
        }
    }

    /**
     * @param $routeName
     * @param SymfonyRoute $route
     */
    public function importRoute($routeName, SymfonyRoute $route) {
        $routeEntity = $this->routeRepo->saveIfNotExists($routeName);
        $routeEntity->setPath($route->getPath());
        $this->routeRepo->save($routeEntity);
        $compiledRoute = $route->compile();
        $requiredType = $this->routeParameterTypeRepo->findRequiredType();
        foreach ($compiledRoute->getVariables() as $parameter) {
            $parameterEntity = $this->routeParameterRepo->saveIfNotExists($routeEntity, $parameter, $requiredType);
            $parameterEntity->setRequirement($route->getRequirement($parameter));
            $parameterEntity->setDefaultValue($route->getDefault($parameter));
            $this->routeParameterRepo->save($parameterEntity);
        }
        // TODO: This, once the bundle is Symfony 3.0 ready
        // if parameter is _scheme or _method - get reqs with those methods? Should we even care?
        // Maybe test this on a project that actually makes use of hosts, schemes and methods requirements for its routes
        // From symfony code:
//        if ('_scheme' === $key) {
//            @trigger_error('The "_scheme" requirement is deprecated since version 2.2 and will be removed in 3.0. Use the setSchemes() method instead.', E_USER_DEPRECATED);
//
//            $this->setSchemes(explode('|', $regex));
//        } elseif ('_method' === $key) {
//            @trigger_error('The "_method" requirement is deprecated since version 2.2 and will be removed in 3.0. Use the setMethods() method instead.', E_USER_DEPRECATED);
//
//            $this->setMethods(explode('|', $regex));
//        }
    }

    public function removeRoute(Route $route) {
        $this->routeRepo->remove($route);
    }

}