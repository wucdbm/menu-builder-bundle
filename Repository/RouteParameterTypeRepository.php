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

use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class RouteParameterTypeRepository extends AbstractRepository {

    public function findRequiredType() {
        $type = $this->findTypeById(RouteParameterType::ID_REQUIRED);

        if ($type) {
            return $type;
        }

        return $this->createType(RouteParameterType::ID_REQUIRED, 'Required');
    }

    public function findOptionalType() {
        $type = $this->findTypeById(RouteParameterType::ID_OPTIONAL);

        if ($type) {
            return $type;
        }

        return $this->createType(RouteParameterType::ID_OPTIONAL, 'Optional');
    }

    public function findQueryStringType() {
        $type = $this->findTypeById(RouteParameterType::ID_QUERY_STRING);

        if ($type) {
            return $type;
        }

        return $this->createType(RouteParameterType::ID_QUERY_STRING, 'Query String');
    }

    public function findTypeById($typeId) {
        $builder = $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id', $typeId);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    protected function createType($id, $name) {
        $type = new RouteParameterType();
        $type->setId($id);
        $type->setName($name);
        $em = $this->getEntityManager();
        $em->persist($type);
        $em->flush($type);

        return $type;
    }

}