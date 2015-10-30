<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Filter\Route;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class RouteParameterFilter extends AbstractFilter {

    /** @var  string */
    protected $parameter;

    /** @var  string */
    protected $name;

    /** @var  Route */
    protected $route;

    /** @var  RouteParameterType */
    protected $type;

    /**
     * @return string
     */
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * @param string $parameter
     */
    public function setParameter($parameter) {
        $this->parameter = $parameter;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return Route
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }

    /**
     * @return RouteParameterType
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param RouteParameterType $type
     */
    public function setType($type) {
        $this->type = $type;
    }

}