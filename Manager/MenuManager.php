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

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuRepository;

class MenuManager extends Manager {

    /**
     * @var MenuRepository
     */
    protected $menuRepository;

    /**
     * MenuManager constructor.
     * @param MenuRepository $menuRepository
     */
    public function __construct(MenuRepository $menuRepository) {
        $this->menuRepository = $menuRepository;
    }

    public function save(Menu $menu) {
        $this->menuRepository->save($menu);
    }

    /**
     * @param $id
     * @return Menu
     */
    public function findOneById($id) {
        return $this->menuRepository->findOneById($id);
    }

}