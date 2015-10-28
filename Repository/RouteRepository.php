<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Repository;

use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class RouteRepository extends AbstractRepository {

    public function saveIfNotExists($routeName) {
        $route = $this->findOneByRoute($routeName);
        if ($route) {
            return $route;
        }
        $route = new Route();
        $route->setRoute($routeName);
        $this->save($route);

        return $route;
    }

    public function findOneByRoute($routeName) {
        $builder = $this->createQueryBuilder('r')
            ->addSelect('items, parameters')
            ->leftJoin('r.items', 'items')
            ->leftJoin('r.parameters', 'parameters')
            ->andWhere('r.route = :route')
            ->setParameter('route', $routeName);
        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function save(Route $route) {
        $em = $this->getEntityManager();
        $em->persist($route);
        $em->flush($route);
    }

}