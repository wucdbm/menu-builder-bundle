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
     * @ORM\Column(name="parameter", type="string", nullable=false)
     */
    protected $parameter;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="is_required", type="boolean", nullable=false)
     */
    protected $isRequired;

    /**
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Route")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    protected $route;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter", mappedBy="parameter")
     */
    protected $values;

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
     * @return mixed
     */
    public function getIsRequired() {
        return $this->isRequired;
    }

    /**
     * @param mixed $isRequired
     */
    public function setIsRequired($isRequired) {
        $this->isRequired = $isRequired;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\Route $route
     *
     * @return $this
     */
    public function setRoute(\Wucdbm\Bundle\MenuBuilderBundle\Entity\Route $route = null) {
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
