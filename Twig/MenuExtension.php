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

use App\Entity\Booking;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu;
use Wucdbm\Bundle\MenuBuilderBundle\Manager\MenuManager;

class MenuExtension extends \Twig_Extension {

    /**
     * @var MenuManager
     */
    protected $manager;

    public function __construct(MenuManager $manager) {
        $this->manager = $manager;
    }

    public function getFilters() {
        return [
            'getMenu' => new \Twig_Filter_Method($this, 'getMenu')
        ];
    }

    public function getFunctions() {
        return [
            'getMenu' => new \Twig_Function_Method($this, 'getMenu'),
            'getMenus' => new \Twig_Function_Method($this, 'getMenus')
        ];
    }

    /**
     * @param $id
     * @return Menu|null
     */
    public function getMenu($id) {
        return $this->manager->findOneById($id);
    }

    /**
     * @return Menu[]
     */
    public function getMenus() {
        return $this->manager->findAll();
    }

    public function getAlias() {
        return 'wucdbm_menu_builder_menu';
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu';
    }

}