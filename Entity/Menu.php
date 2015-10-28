<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_menus")
 */
class Menu {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="alias", type="string", nullable=false)
     */
    protected $alias;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu", mappedBy="menu")
     */
    protected $items;

}
