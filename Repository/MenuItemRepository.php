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

use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class MenuItemRepository extends AbstractRepository {

    /**
     * @param $id
     * @return MenuItem
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($id) {
        $builder = $this->createQueryBuilder('i')
            ->andWhere('i.id = :id')
            ->setParameter('id', $id);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function remove(MenuItem $item) {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($item->getChildren() as $child) {
                $this->remove($child);
            }
            
            foreach ($item->getParameters() as $parameter) {
                $em->remove($parameter);
            }

            $em->remove($item);
            $em->flush();

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public function save(MenuItem $item) {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($item->getParameters() as $parameter) {
                $em->persist($parameter);
            }

            $em->persist($item);
            $em->flush();

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

}