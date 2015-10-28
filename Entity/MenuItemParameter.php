<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuItemParameterRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_menus_items_parameters")
 */
class MenuItemParameter {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="value", type="string", nullable=false)
     */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem", inversedBy="parameters")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=false)
     */
    protected $item;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter", inversedBy="values")
     * @ORM\JoinColumn(name="parameter_id", referencedColumnName="id", nullable=false)
     */
    protected $parameter;

}
