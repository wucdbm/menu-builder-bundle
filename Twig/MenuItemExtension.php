<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Twig;

use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
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
            'menuItemPath' => new \Twig_Filter_Method($this, 'menuItemPath')
        );
    }

    public function getFunctions() {
        return array(
            'menuItemPath' => new \Twig_Function_Method($this, 'menuItemPath')
        );
    }

    public function menuItemPath(MenuItem $item) {
        $route = $item->getRoute()->getRoute();
        $parameters = [];
        /** @var MenuItemParameter $parameter */
        foreach ($item->getParameters() as $parameter) {
            $key = $parameter->getParameter()->getParameter();
            $value = $parameter->getValue();
            $parameters[$key] = $value;
        }

        return $this->router->generate($route, $parameters, Router::ABSOLUTE_PATH);
    }

    public function getAlias() {
        return 'wucdbm_menu_builder_menu_item';
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item';
    }

}