<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Form\Menu;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType;
use Wucdbm\Bundle\WucdbmBundle\Form\AbstractType;

class MenuItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Display name - this will be displayed as the text of the generated hyperlink'
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var MenuItem $item */
            $item = $event->getData();
            $route = $item->getRoute();

            $routeParameters = [];
            /** @var MenuItemParameter $parameter */
            foreach ($item->getParameters() as $parameter) {
                $routeParameter = $parameter->getParameter();
                $routeParameters[$routeParameter->getId()] = $routeParameter;
            }

            /** @var RouteParameter $parameter */
            foreach ($route->getParameters() as $parameter) {
                if (isset($routeParameters[$parameter->getId()])) {
                    continue;
                }
                // Add missing required parameters
                if ($parameter->getType()->getId() == RouteParameterType::ID_REQUIRED) {
                    $menuParameter = new MenuItemParameter();
                    $menuParameter->setParameter($parameter);
                    $menuParameter->setItem($item);
                    $item->addParameter($menuParameter);
                }
            }
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
            $duplicateValidator = function ($object, ExecutionContextInterface $context) use ($builder) {
                /** @var MenuItem $item */
                $item = $builder->getData();
                $duplicates = [];
                /** @var MenuItemParameter $menuParameter */
                foreach ($item->getParameters() as $menuParameter) {
                    $parameter = $menuParameter->getParameter();
                    $key = $item->getId() . '_' . $parameter->getId();
                    if (isset($duplicates[$key])) {
                        $context->buildViolation('A duplicate entry for Parameter "' . $parameter->getParameter() . '" was found')->addViolation();
                    }
                    $duplicates[$key] = true;
                }
            };

            $requiredValidator = function ($object, ExecutionContextInterface $context) use ($builder) {
                /** @var MenuItem $item */
                $item = $builder->getData();
                $route = $item->getRoute();
                $required = [];
                /** @var RouteParameter $parameter */
                foreach ($route->getParameters() as $parameter) {
                    if ($parameter->getType()->getId() == RouteParameterType::ID_REQUIRED) {
                        $required[$parameter->getId()] = $parameter;
                    }
                }

                /** @var MenuItemParameter $menuParameter */
                foreach ($item->getParameters() as $menuParameter) {
                    $parameter = $menuParameter->getParameter();
                    unset($required[$parameter->getId()]);
                }

                /** @var RouteParameter $parameter */
                foreach ($required as $parameter) {
                    $context->buildViolation('The Required Parameter "' . $parameter->getParameter() . '" is missing')->addViolation();
                }
            };

            $form = $event->getForm();
            /** @var MenuItem $item */
            $item = $event->getData();
            $form->add('parameters', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', [
                // TODO: Make it possible to add custom parameters with custom names
//                'allow_add'    => true,
//                'allow_delete' => true,
                'entry_type'  => new MenuItemParameterType($item),
                'constraints' => [
                    new Callback([
                        'callback' => $duplicateValidator
                    ]),
                    new Callback([
                        'callback' => $requiredValidator
                    ])
                ]
            ]);
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var MenuItem $item */
            $item = $event->getData();
            /** @var MenuItemParameter $menuParameter */
            foreach ($item->getParameters() as $menuParameter) {
                $menuParameter->setItem($item);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem'
        ]);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item';
    }
}