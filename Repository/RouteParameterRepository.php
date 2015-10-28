<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Repository;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class RouteParameterRepository extends AbstractRepository {

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

    /**
     * @param Route $route
     * @param $parameter
     * @return RouteParameter
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByRouteAndParameter(Route $route, $parameter) {
        $builder = $this->createQueryBuilder('p')
            ->addSelect('t, r, v')
            ->leftJoin('p.type', 't')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.values', 'v')
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