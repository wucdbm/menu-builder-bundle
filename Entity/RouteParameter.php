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
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterRepository")
 * @ORM\Table(name="_wucdbm_menu_builder_routes_parameters",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="route_parameter", columns={"route_id", "parameter"})
 *      }
 * )
 */
class RouteParameter {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="parameter", type="string", nullable=false)
     */
    protected $parameter;

    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Route", inversedBy="parameters")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    protected $route;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter", mappedBy="parameter")
     */
    protected $values;

    /**
     * Constructor
     */
    public function __construct() {
        $this->values = new ArrayCollection();
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
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * @param mixed $parameter
     */
    public function setParameter($parameter) {
        $this->parameter = $parameter;
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
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\Route $route
     *
     * @return $this
     */
    public function setRoute(\Wucdbm\Bundle\MenuBuilderBundle\Entity\Route $route) {
        $this->route = $route;

        return $this;
    }

    /**
     * @return \Wucdbm\Bundle\MenuBuilderBundle\Entity\Route
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType $type
     *
     * @return $this
     */
    public function setType(\Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType $type) {
        $this->type = $type;

        return $this;
    }

    /**
     * @return \Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Add booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $value
     *
     * @return $this
     */
    public function addValue(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $value) {
        $this->values[] = $value;

        return $this;
    }

    /**
     * Remove booking
     *
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $value
     */
    public function removeValue(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $value) {
        $this->values->removeElement($value);
    }

    /**
     * Get bookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValues() {
        return $this->values;
    }

}
