<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Twig;

use App\Entity\Booking;
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
            'menuItemPath' => new \Twig_Filter_Method($this, 'menuItemPath'),
            'menuItemUrl'  => new \Twig_Filter_Method($this, 'menuItemUrl')
        );
    }

    public function getFunctions() {
        return array(
            'menuItemPath' => new \Twig_Function_Method($this, 'menuItemPath'),
            'menuItemUrl'  => new \Twig_Function_Method($this, 'menuItemUrl')
        );
    }

    public function menuItemPath(MenuItem $item) {
        return $this->url($item, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function menuItemUrl(MenuItem $item, $type = UrlGeneratorInterface::ABSOLUTE_URL) {
        return $this->url($item, $type);
    }

    protected function url(MenuItem $item, $type) {
        $route = $item->getRoute()->getRoute();
        $parameters = [];
        /** @var MenuItemParameter $parameter */
        foreach ($item->getParameters() as $parameter) {
            $key = $parameter->getParameter()->getParameter();
            $value = $parameter->getValue();
            $parameters[$key] = $value;
        }

        return $this->router->generate($route, $parameters, $type);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item';
    }

}