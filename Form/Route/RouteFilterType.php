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
use Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteFilter;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\BaseFilterType;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\ChoiceFilterType;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType;

class RouteFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('isNamed', ChoiceFilterType::class, [
                'placeholder'       => 'Is named filter',
                'choices'           => [
                    'Only NOT named' => RouteFilter::IS_NAMED_FALSE,
                    'Only named'     => RouteFilter::IS_NAMED_TRUE
                ],
                'choices_as_values' => true
            ])
            ->add('name', TextFilterType::class, [
                'placeholder' => 'Name'
            ])
            ->add('route', TextFilterType::class, [
                'placeholder' => 'Route'
            ])
            ->add('parameterName', TextFilterType::class, [
                'placeholder' => 'Parameter Name'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteFilter'
        ));
    }

}
