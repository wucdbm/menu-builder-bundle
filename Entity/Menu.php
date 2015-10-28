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
     * @return mixed
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias) {
        $this->alias = $alias;
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

}
