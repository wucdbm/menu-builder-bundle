<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_routes",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="route", columns={"route"})
 *      }
 * )
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
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem", mappedBy="route")
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter", mappedBy="route")
     */
    protected $parameters;

    /**
     * Constructor
     */
    public function __construct() {
        $this->items = new ArrayCollection();
        $this->parameters = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route) {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Add booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $item
     *
     * @return $this
     */
    public function addItem(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $item) {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $item
     */
    public function removeItem(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $item) {
        $this->items->removeElement($item);
    }

    /**
     * Get bookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems() {
        return $this->items;
    }

    /**
     * Add booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter $parameter
     *
     * @return $this
     */
    public function addParameter(\Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter $parameter) {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter $parameter
     */
    public function removeParameter(\Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter $parameter) {
        $this->parameters->removeElement($parameter);
    }

    /**
     * Get bookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters() {
        return $this->parameters;
    }

}
