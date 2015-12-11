<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Repository;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Menu\MenuFilter;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class MenuRepository extends AbstractRepository {

    public function remove(Menu $menu) {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($menu->getItems() as $item) {
                foreach ($item->getParameters() as $parameter) {
                    $em->remove($parameter);
                }
                $em->remove($item);
            }

            $em->remove($menu);
            $em->flush();

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function filter(MenuFilter $filter) {
        $builder = $this->getQueryBuilder();

        if ($filter->getName()) {
            $builder->andWhere('m.name = :name')
                ->setParameter('name', '%' . $filter->getName() . '%');
        }

        $route = $filter->getRoute();
        if ($route instanceof Route) {
            $builder->andWhere('r.id = :routeId')
                ->setParameter('routeId', $route->getId());
        }

        return $this->returnFilteredEntities($builder, $filter, 'm.id');
    }

    /**
     * @param $id
     * @return Menu
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($id) {
        $builder = $this->getQueryBuilder()
            ->andWhere('m.id = :id')
            ->setParameter('id', $id);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getQueryBuilder() {
        return $this->createQueryBuilder('m')
            ->addSelect('i, p, r')
            ->leftJoin('m.items', 'i', null, null, 'i.id')
            ->leftJoin('i.parameters', 'p')
            ->leftJoin('i.route', 'r');
    }

    public function save(Menu $menu) {
        $em = $this->getEntityManager();
        $em->persist($menu);
        $em->flush($menu);
    }

}