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

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteParameterFilter;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class RouteParameterRepository extends AbstractRepository {

    public function filter(RouteParameterFilter $filter) {
        $builder = $this->getQueryBuilder();

        if ($filter->getName()) {
            $builder->andWhere('p.name = :name')
                ->setParameter('name', '%' . $filter->getName() . '%');
        }

        if ($filter->getParameter()) {
            $builder->andWhere('p.parameter = :parameter')
                ->setParameter('parameter', '%' . $filter->getParameter() . '%');
        }

        $route = $filter->getRoute();
        if ($route instanceof Route) {
            $builder->andWhere('r.id = :routeId')
                ->setParameter('routeId', $route->getId());
        }

        $type = $filter->getType();
        if ($type instanceof RouteParameterType) {
            $builder->andWhere('t.id = :typeId')
                ->setParameter('typeId', $type->getId());
        }

        return $this->returnFilteredEntities($builder, $filter, 'p.id');
    }

    /**
     * @param $id
     * @return RouteParameter
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneById($id) {
        $builder = $this->getQueryBuilder()
            ->andWhere('p.id = :id')
            ->setParameter('id', $id);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getQueryBuilder() {
        return $this->createQueryBuilder('p')
            ->addSelect('t, r, v')
            ->leftJoin('p.type', 't')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.values', 'v');
    }

    public function saveIfNotExists(Route $route, $parameter, RouteParameterType $type) {
        $parameterEntity = $this->findOneByRouteAndParameter($route, $parameter);
        if ($parameterEntity) {
            $parameterEntity->setType($type);
            $this->save($parameterEntity);

            return $parameterEntity;
        }
        $parameterEntity = new RouteParameter();
        $parameterEntity->setRoute($route);
        $route->addParameter($parameterEntity);
        $parameterEntity->setParameter($parameter);
        $parameterEntity->setType($type);
        $this->save($parameterEntity);

        return $route;
    }

    public function getParametersByRouteQueryBuilder(Route $route) {
        return $this->getQueryBuilder()
            ->andWhere('r.id = :routeId')
            ->setParameter('routeId', $route->getId());
    }

    /**
     * @param Route $route
     * @param $parameter
     * @return RouteParameter
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRouteAndParameter(Route $route, $parameter) {
        $builder = $this->getQueryBuilder()
            ->andWhere('r.id = :routeId')
            ->setParameter('routeId', $route->getId())
            ->andWhere('p.parameter = :parameter')
            ->setParameter('parameter', $parameter);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function save(RouteParameter $parameter) {
        $em = $this->getEntityManager();
        $em->persist($parameter);
        $em->flush($parameter);
    }

}