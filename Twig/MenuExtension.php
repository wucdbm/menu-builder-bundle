<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Twig;

use App\Entity\Booking;
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
            'getMenu' => new \Twig_Function_Method($this, 'getMenu')
        ];
    }

    public function getMenu($id) {
        return $this->manager->findOneById($id);
    }

    public function getAlias() {
        return 'wucdbm_menu_builder_menu';
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu';
    }

}