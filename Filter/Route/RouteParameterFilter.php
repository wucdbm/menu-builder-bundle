<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Filter\Route;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class RouteParameterFilter extends AbstractFilter {

    const IS_NAMED_TRUE = 1,
        IS_NAMED_FALSE = 2;

    /** @var  string */
    protected $parameter;

    /** @var  string */
    protected $name;

    /** @var  Route */
    protected $route;

    /** @var  RouteParameterType */
    protected $type;

    /** @var  integer */
    protected $isNamed;

    /**
     * @return int
     */
    public function getIsNamed() {
        return $this->isNamed;
    }

    /**
     * @param int $isNamed
     */
    public function setIsNamed($isNamed) {
        $this->isNamed = $isNamed;
    }

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