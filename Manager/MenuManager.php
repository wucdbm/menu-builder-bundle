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

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuRepository;

class MenuManager extends Manager {

    /**
     * @var MenuRepository
     */
    protected $menuRepository;

    /**
     * @var Router
     */
    protected $router;

    /**
     * MenuManager constructor.
     * @param MenuRepository $menuRepository
     * @param Router $router
     */
    public function __construct(MenuRepository $menuRepository, Router $router) {
        $this->menuRepository = $menuRepository;
        $this->router = $router;
    }

    public function create() {
        return new Menu();
    }

    public function createItem() {
        return new MenuItem();
    }

    public function save(Menu $menu) {
        $menu->setDateModified(new \DateTime());
        $this->menuRepository->save($menu);
    }

    /**
     * @param $id
     * @return Menu|null
     */
    public function findOneById($id) {
        return $this->menuRepository->findOneById($id);
    }

    /**
     * @return Menu[]
     */
    public function findAll() {
        return $this->menuRepository->findAll();
    }

    public function generateMenuItemUrl(MenuItem $item, $type = UrlGeneratorInterface::ABSOLUTE_URL) {
        if ($item->getUrl()) {
            return $item->getUrl();
        }

        $route = $item->getRoute()->getRoute();
        $parameters = [];
        /** @var MenuItemParameter $parameter */
        foreach ($item->getParameters() as $parameter) {
            $key = $parameter->getParameter()->getParameter();
            $parameters[$key] = $this->getValue($parameter, $this->router->getContext());
        }

        return $this->router->generate($route, $parameters, $type);
    }

    protected function getValue(MenuItemParameter $parameter, RequestContext $context) {
        if ($parameter->getUseValueFromContext()) {
            $routeParameter = $parameter->getParameter();

            // If the current context has this parameter, use it
            if ($context->hasParameter($routeParameter->getParameter())) {
                return $context->getParameter($routeParameter->getParameter());
            }

            // Otherwise, use the default value for this route
            // Note: This might change, and upon importing routes anew
            // The URLs generated will now use the new default value
            $default = $routeParameter->getDefaultValue();
            if ($default) {
                return $default;
            }
        }

        // If no value was found in the context or the default route parameter value
        // return the last copy of its default

        return $parameter->getValue();
    }

}