<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Filter\Menu;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class MenuFilter extends AbstractFilter {

    /** @var  string */
    protected $name;

    /** @var  Route */
    protected $route;

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

}