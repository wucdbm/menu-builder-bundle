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
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\Route", inversedBy="items")
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
     */
    protected $route;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter", mappedBy="item")
     */
    protected $parameters;

    /**
     * Constructor
     */
    public function __construct() {
        $this->items = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu $menu
     *
     * @return $this
     */
    public function setMenu(\Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu $menu = null) {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return \Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu
     */
    public function getMenu() {
        return $this->menu;
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
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $parameter
     *
     * @return $this
     */
    public function addParameter(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $parameter) {
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $parameter
     */
    public function removeParameter(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter $parameter) {
        $this->parameters->removeElement($parameter);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters() {
        return $this->parameters;
    }

}
