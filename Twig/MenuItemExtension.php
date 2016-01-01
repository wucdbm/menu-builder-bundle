<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter;

class MenuItemExtension extends \Twig_Extension {

    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('menuItemUrl', [$this, 'menuItemUrl']),
            new \Twig_SimpleFilter('menuItemPath', [$this, 'menuItemPath'])
        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('menuItemUrl', [$this, 'menuItemUrl']),
            new \Twig_SimpleFunction('menuItemPath', [$this, 'menuItemPath'])
        );
    }

    public function menuItemUrl(MenuItem $item, $type = UrlGeneratorInterface::ABSOLUTE_URL) {
        return $this->url($item, $type);
    }

    public function menuItemPath(MenuItem $item) {
        return $this->url($item, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    protected function url(MenuItem $item, $type) {
        $route = $item->getRoute()->getRoute();
        $parameters = [];
        /** @var MenuItemParameter $parameter */
        foreach ($item->getParameters() as $parameter) {
            $key = $parameter->getParameter()->getParameter();
            $value = $this->getValue($parameter);
            $parameters[$key] = $value;
        }

        return $this->router->generate($route, $parameters, $type);
    }

    protected function getValue(MenuItemParameter $parameter) {
        if ($parameter->getUseDefaultValue()) {
            $routeParameter = $parameter->getParameter();
            $routeParameterDefaultValue = $routeParameter->getDefaultValue();
            if ($routeParameterDefaultValue) {
                return $routeParameterDefaultValue;
            }
            // If configured to use the default value, but the default value is no longer present for the RouteParameter
            // return value instead because the RouteParameter default value has been saved in the value field
            // when this MenuItemParameter was created
        }

        return $parameter->getValue();
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item';
    }

}