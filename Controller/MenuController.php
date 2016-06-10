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
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Menu;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Menu\MenuFilter;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Menu\CreateType;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Menu\MenuFilterType;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Menu\MenuItemType;
use Wucdbm\Bundle\MenuBuilderBundle\Form\Menu\RouteChoiceType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class MenuController extends BaseController {

    public function listAction(Request $request) {
        $repo = $this->get('wucdbm_menu_builder.repo.menus');
        $filter = new MenuFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(MenuFilterType::class, $filter);
        $filter->load($request, $filterForm);
        $menus = $repo->filter($filter);
        $data = [
            'menus'      => $menus,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/list.html.twig', $data);
    }

    public function refreshListRowAction(Menu $menu) {
        $data = [
            'menu' => $menu
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/list_row.html.twig', $data);
    }

    public function makeSystemAction($id) {
        return $this->system($id, true);
    }

    public function makePublicAction($id) {
        return $this->system($id, false);
    }

    protected function system($id, $boolean) {
        $repo = $this->container->get('wucdbm_menu_builder.repo.menus');
        $menu = $repo->findOneById($id);
        
        if (!$menu) {
            return $this->witter([
                'text' => 'Menu not found'
            ]);
        }
        
        $menu->setIsSystem($boolean);
        $repo->save($menu);

        return $this->json([
            'success' => true,
            'refresh' => true
        ]);
    }

    public function createAction(Request $request) {
        $menu = new Menu();
        $form = $this->createForm(CreateType::class, $menu);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_menu_builder.manager.menus');
            $manager->save($menu);

            return $this->redirectToRoute('wucdbm_menu_builder_menu_edit', [
                'id' => $menu->getId()
            ]);
        }

        $data = [
            'form' => $form->createView(),
            'menu' => $menu
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/create.html.twig', $data);
    }

    public function editAction(Menu $menu) {
        $data = [
            'menu' => $menu
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/edit.html.twig', $data);
    }

    public function refreshEditAction(Menu $menu) {
        $data = [
            'menu' => $menu
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/view/content.html.twig', $data);
    }

    public function previewAction(Menu $menu) {
        $data = [
            'menu' => $menu
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/preview.html.twig', $data);
    }

    public function updateNameAction($id, Request $request) {
        $post = $request->request;
        // name, value, pk
        $name = $post->get('value', null);

        if (null === $name) {
            return new Response('Error - Empty value', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $repo = $this->container->get('wucdbm_menu_builder.repo.menus');
        $menu = $repo->findOneById($id);
        $menu->setName($name);
        $repo->save($menu);

        return new Response();
    }

    public function updateItemNameAction($id, Request $request) {
        $post = $request->request;
        // name, value, pk
        $name = $post->get('value', null);

        if (null === $name) {
            return new Response('Error - Empty value', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $repo = $this->container->get('wucdbm_menu_builder.repo.menus_items');
        $item = $repo->findOneById($id);
        $item->setName($name);
        $repo->save($item);

        return new Response();
    }

    public function removeItemAction(Menu $menu, $itemId, Request $request) {
        $menuItemRepository = $this->container->get('wucdbm_menu_builder.repo.menus_items');
        $item = $menuItemRepository->findOneById($itemId);
        $menuItemRepository->remove($item);

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                'refresh' => true
            ]);
        }

        return $this->redirectToRoute('wucdbm_menu_builder_menu_edit', [
            'id' => $menu->getId()
        ]);
    }

    public function removeAction(Menu $menu, Request $request) {
        if ($request->isXmlHttpRequest()) {
            if ($menu->getIsSystem()) {
                return $this->json([
                    'witter' => [
                        'title' => sprintf('Failed removing Menu %s', $menu->getName()),
                        'text'  => sprintf('You can not delete Menu %s because it is a System menu.', $menu->getItems())
                    ]
                ]);
            }

            $isConfirmed = $request->request->get('is_confirmed');

            if ($isConfirmed) {
                $menuRepository = $this->container->get('wucdbm_menu_builder.repo.menus');
                $menuRepository->remove($menu);

                return $this->json([
                    'redirect' => $this->generateUrl('wucdbm_menu_builder_menu_list')
                ]);
            }

            return $this->json([
                'witter' => [
                    'title' => 'You must confirm this action',
                    'text'  => 'You must confirm first in order to delete this Menu'
                ]
            ]);
        }

        $referer = $request->headers->get('Referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('wucdbm_menu_builder_menu_list');
    }

    public function addItemAction(Menu $menu, Request $request) {
        $item = new MenuItem();
        $form = $this->createForm(RouteChoiceType::class, $item);

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirectToRoute('wucdbm_menu_builder_menu_items_add_by_route', [
                'id'      => $menu->getId(),
                'routeId' => $item->getRoute()->getId()
            ]);
        }

        $data = [
            'menu' => $menu,
            'form' => $form->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/add_item.html.twig', $data);
    }

    public function addItemByRouteAction(Menu $menu, $routeId, Request $request) {
        $routeRepository = $this->container->get('wucdbm_menu_builder.repo.routes');
        $route = $routeRepository->findOneById($routeId);
        $item = new MenuItem();
        $item->setRoute($route);
        $item->setMenu($menu);
        $menu->addItem($item);

        return $this->editItemAction($item, $request);
    }

    public function addSubItemAction(Menu $menu, $itemId, Request $request) {
        $item = new MenuItem();
        $form = $this->createForm(RouteChoiceType::class, $item);

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirectToRoute('wucdbm_menu_builder_menu_items_add_sub_by_route', [
                'id'      => $menu->getId(),
                'itemId'  => $itemId,
                'routeId' => $item->getRoute()->getId()
            ]);
        }

        $data = [
            'menu' => $menu,
            'form' => $form->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/add_item.html.twig', $data);
    }

    public function addSubItemByRouteAction(Menu $menu, $itemId, $routeId, Request $request) {
        $routeRepository = $this->container->get('wucdbm_menu_builder.repo.routes');
        $menuItemRepository = $this->container->get('wucdbm_menu_builder.repo.menus_items');
        $parent = $menuItemRepository->findOneById($itemId);
        $route = $routeRepository->findOneById($routeId);
        $item = new MenuItem();
        $item->setRoute($route);
        $item->setMenu($menu);
        $item->setParent($parent);
        $parent->addChild($item);
        $menu->addItem($item);

        return $this->editItemAction($item, $request);
    }

    public function editItemAction(MenuItem $item, Request $request) {
        $form = $this->createForm(MenuItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $menuItemRepository = $this->container->get('wucdbm_menu_builder.repo.menus_items');
            $menuItemRepository->save($item);

            return $this->redirectToRoute('wucdbm_menu_builder_menu_edit', [
                'id' => $item->getMenu()->getId()
            ]);
        }

        $data = [
            'menu'  => $item->getMenu(),
            'route' => $item->getRoute(),
            'item'  => $item,
            'form'  => $form->createView()
        ];

        return $this->render('@WucdbmMenuBuilder/Menu/add_item_by_route.html.twig', $data);
    }

}