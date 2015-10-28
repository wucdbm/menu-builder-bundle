<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuItemRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_menus_items")
 */
class MenuItem {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu", inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", nullable=false)
     */
    protected $menu;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Route")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    protected $route;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter", mappedBy="item")
     */
    protected $parameters;

}
