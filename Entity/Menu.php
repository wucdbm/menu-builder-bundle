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
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\MenuBuilderBundle\Repository\MenuRepository")
 * @ORM\Table(name="_wucdbm__menu_builder_menus")
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
     * @ORM\Column(name="is_system", type="boolean", nullable=false)
     */
    protected $isSystem = false;

    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem", mappedBy="menu")
     */
    protected $items;

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
     * @return boolean
     */
    public function getIsSystem() {
        return $this->isSystem;
    }

    /**
     * @param boolean $isSystem
     */
    public function setIsSystem($isSystem) {
        $this->isSystem = $isSystem;
    }

}
