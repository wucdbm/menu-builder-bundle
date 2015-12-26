<?php

/*
 * This file is part of the MenuBuilderBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\MenuBuilderBundle\Form\Route;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteParameterFilter;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteRepository;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\BaseFilterType;

class RouteParameterFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('isNamed', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\ChoiceFilterType', [
                'placeholder' => 'Is named filter',
                'choices'     => [
                    'Only NOT named' => RouteParameterFilter::IS_NAMED_FALSE,
                    'Only named'     => RouteParameterFilter::IS_NAMED_TRUE
                ]
            ])
            ->add('parameter', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'Parameter'
            ])
            ->add('name', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'Name'
            ])
            ->add('route', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\EntityFilterType', [
                'class'         => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\Route',
                'query_builder' => function (RouteRepository $repository) {
                    return $repository->getRoutesWithParametersQueryBuilder();
                },
                'choice_label'  => 'route',
                'placeholder'   => 'Route'
            ])
            ->add('type', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\EntityFilterType', [
                'class'        => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType',
                'choice_label' => 'name',
                'placeholder'  => 'Type'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteParameterFilter'
        ));
    }

}
