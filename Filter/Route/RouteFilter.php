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

    protected $name;

    protected $route;

    protected $isNamed;

    /**
     * @return mixed
     */
    public function getIsNamed() {
        return $this->isNamed;
    }

    /**
     * @param mixed $isNamed
     */
    public function setIsNamed($isNamed) {
        $this->isNamed = $isNamed;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }

}