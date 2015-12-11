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
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

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
     * @ORM\ManyToOne(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem", mappedBy="parent")
     */
    protected $children;

    /**
     * Constructor
     */
    public function __construct() {
        $this->parameters = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu $menu
     *
     * @return $this
     */
    public function setMenu(\Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu $menu) {
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

    // TODO:

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $parent
     *
     * @return $this
     */
    public function setParent(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $parent) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return \Wucdbm\Bundle\MenuBuilderBundle\Entity\Route
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $child
     *
     * @return $this
     */
    public function addChild(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $child) {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param \Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $chid
     */
    public function removeChild(\Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem $chid) {
        $this->children->removeElement($chid);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren() {
        return $this->children;
    }

}
