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

use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class RouteFilter extends AbstractFilter {

    const IS_NAMED_TRUE = 1,
        IS_NAMED_FALSE = 2;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $route;

    /** @var  string */
    protected $parameterName;

    /** @var  integer */
    protected $isNamed;

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
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }

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
    public function getParameterName() {
        return $this->parameterName;
    }

    /**
     * @param string $parameterName
     */
    public function setParameterName($parameterName) {
        $this->parameterName = $parameterName;
    }

}