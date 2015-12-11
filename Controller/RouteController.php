<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteFilter;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteParameterFilter;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Route\RouteFilterType;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Route\RouteParameterFilterType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class RouteController extends BaseController {

    public function listRoutesAction(Request $request) {
        $repo = $this->get('wucdbm_menu_builder.repo.routes');
        $filter = new RouteFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(new RouteFilterType(), $filter);
        $filter->load($request, $filterForm);
        $routes = $repo->filter($filter);
        $data = [
            'routes'     => $routes,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Route/routes/list.html.twig', $data);
    }

    public function updateRouteNameAction($id, Request $request) {
        $post = $request->request;
        // name, value, pk
        $name = $post->get('value', null);

        if (null === $name) {
            return new Response('Error - Empty value', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $repo = $this->container->get('wucdbm_menu_builder.repo.routes');
        $route = $repo->findOneById($id);
        $route->setName($name);
        $repo->save($route);

        return new Response();
    }

    public function listParametersAction(Request $request) {
        $repo = $this->get('wucdbm_menu_builder.repo.routes_parameters');
        $filter = new RouteParameterFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(new RouteParameterFilterType(), $filter);
        $filter->load($request, $filterForm);
        $parameters = $repo->filter($filter);
        $data = [
            'parameters' => $parameters,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Route/parameters/list.html.twig', $data);
    }

    public function updateRouteParameterNameAction($id, Request $request) {
        $post = $request->request;
        // name, value, pk
        $name = $post->get('value', null);

        if (null === $name) {
            return new Response('Error - Empty value', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $repo = $this->container->get('wucdbm_menu_builder.repo.routes_parameters');
        $parameter = $repo->findOneById($id);
        $parameter->setName($name);
        $repo->save($parameter);

        return new Response();
    }

}