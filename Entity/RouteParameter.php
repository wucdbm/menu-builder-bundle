<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_routes_parameters")
 */
// TODO: Maybe RouteParameterType
// 1. Required
// 2. Optional
class RouteParameter {

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
     * @ORM\Column(name="display_name", type="string", nullable=false)
     */
    protected $displayName;

    /**
     * @ORM\Column(name="is_required", type="boolean", nullable=false)
     */
    protected $isRequired = false;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Route")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    protected $route;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter", mappedBy="parameter")
     */
    protected $values;

}
