<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_routes")
 */
class Route {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="route", type="string", nullable=false)
     */
    protected $route;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

}
