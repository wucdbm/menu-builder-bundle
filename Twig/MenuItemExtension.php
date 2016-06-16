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
use Symfony\Component\Routing\RequestContext;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Manager\MenuManager;

class MenuItemExtension extends \Twig_Extension {

    /** @var MenuManager */
    protected $manager;

    public function __construct(MenuManager $manager) {
        $this->manager = $manager;
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
        return $this->manager->generateMenuItemUrl($item, $type);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item';
    }

}