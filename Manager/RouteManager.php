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

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
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
     * @param Router $router
     */
    public function importRouter(Router $router) {
        /** @var $collection RouteCollection */
        $collection = $router->getRouteCollection();

        $allRoutes = $collection->all();

        /**
         * @var string $routeName
         * @var Route $route
         */
        foreach ($allRoutes as $routeName => $route) {
            $this->importRoute($routeName, $route);
        }
    }

    /**
     * @param $routeName
     * @param Route $route
     */
    public function importRoute($routeName, Route $route) {
        $routeEntity = $this->routeRepo->saveIfNotExists($routeName);
        $compiledRoute = $route->compile();
        $requiredType = $this->routeParameterTypeRepo->findRequiredType();
        foreach ($compiledRoute->getVariables() as $parameter) {
            $this->routeParameterRepo->saveIfNotExists($routeEntity, $parameter, $requiredType);
        }
    }

}