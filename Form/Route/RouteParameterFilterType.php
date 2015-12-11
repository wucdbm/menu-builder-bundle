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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\Route;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteRepository;

class RouteParameterFilterType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
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
                'property'      => 'route',
                'placeholder'   => 'Route'
            ])
            ->add('type', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\EntityFilterType', [
                'class'       => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameterType',
                'property'    => 'name',
                'placeholder' => 'Type'
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteParameterFilter'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return '';
    }

    public function getParent() {
        return 'wucdbm_filter_basic';
    }

}
