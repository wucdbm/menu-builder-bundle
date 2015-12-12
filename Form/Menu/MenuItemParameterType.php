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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterRepository;

class MenuItemParameterType extends AbstractType {

    /** @var MenuItem */
    protected $item;

    public function __construct(MenuItem $item) {
        $this->item = $item;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $item = $this->item;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($item) {
            $rawData = $event->getData();
            $form = $event->getForm();
            /** @var MenuItemParameter $data */
            $data = $form->getData();
            $requirement = $data->getParameter()->getRequirement();
            $choices = $this->getChoices($requirement);
            if ($choices) {
                $choices[] = $rawData['value'];
                $form->remove('value');
                $this->addValueChoiceField($form, $choices, $requirement);
            }
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($item) {
            /** @var MenuItemParameter $data */
            $data = $event->getData();
            $form = $event->getForm();

            $parameter = $data->getParameter();
            $parameterName = $parameter->getName() ? $parameter->getName() : $parameter->getParameter();
            $default = $parameter->getDefaultValue();

            if ($default) {
                $data->setValue($default);
            }

            $requirement = $parameter->getRequirement();
            $choices = $this->getChoices($requirement);

            if ($choices) {
                $this->addValueChoiceField($form, $choices, $requirement);
            } else {
                $form
                    ->add('value', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                        'label'       => sprintf('Value for parameter "%s"', $parameterName),
                        'attr'        => [
                            'placeholder' => $default ? sprintf('%s (Default: %s)', $parameterName, $default) : $parameterName
                        ],
                        'constraints' => [
                            $this->createNotBlankConstraint(),
                            $this->createRegexConstaint($requirement)
                        ]
                    ]);
            }

            $form
                ->add('parameter', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                    'class'         => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter',
                    'disabled'      => true,
                    'choice_label'  => function (RouteParameter $parameter) {
                        if ($parameter->getRequirement()) {
                            return sprintf('%s (%s)', $parameter->getParameter(), $parameter->getRequirement());
                        }

                        return $parameter->getParameter();
                    },
                    'query_builder' => function (RouteParameterRepository $repository) use ($item) {
                        $route = $item->getRoute();

                        return $repository->getParametersByRouteQueryBuilder($route);
                    },
                    'attr'          => [
                        'rel'          => 'popover',
                        'title'        => $parameterName,
                        'data-content' => sprintf('Parameter %s with requirements %s', $parameterName, $this->getRegexPattern($requirement))
                    ],
                    'constraints'   => [
                        new NotBlank([
                            'message' => 'This value is required'
                        ])
                    ]
                ]);
        });
    }

    protected function createNotBlankConstraint() {
        return new NotBlank([
            'message' => 'This field is required'
        ]);
    }

    protected function createRegexConstaint($requirement = null) {
        $regex = $this->getRegexPattern($requirement);

        $message = sprintf('The given value does not match the requirements: %s', $regex);

        if ('\d+' == $requirement) {
            $message = sprintf('The given value must be numeric. Regex: %s', $regex);
        }

        return new Regex([
            'pattern' => $regex,
            'message' => $message
        ]);
    }

    protected function getRegexPattern($requirement) {
        if (null === $requirement) {
            $requirement = '[^/]++';
        }

        return '#^(' . $requirement . ')$#';
    }

    protected function getChoices($requirement) {
        if (false === strpos($requirement, '|')) {
            return [];
        }

        $choices = explode('|', $requirement);
        foreach ($choices as $key => $choice) {
            if (false !== strpbrk($choice, '.+*?')) {
                unset($choices[$key]);
            }
        }

        return $choices;
    }

    protected function addValueChoiceField(FormInterface $form, $choices, $requirement) {
        $form->add('value', 'Wucdbm\Bundle\MenuBuilderBundle\Form\Menu\MenuItemParameterChoiceType', [
            'choices'     => $choices,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createRegexConstaint($requirement)
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter',
            'label'      => false
        ]);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item_parameter';
    }
}