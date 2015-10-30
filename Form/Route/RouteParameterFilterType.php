<?php

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
            ->add('parameter', 'wucdbm_filter_text', [
                'placeholder' => 'Parameter'
            ])
            ->add('name', 'wucdbm_filter_text', [
                'placeholder' => 'Name'
            ])
            ->add('route', 'wucdbm_filter_entity', [
                'class'         => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\Route',
                'query_builder' => function (RouteRepository $repository) {
                    return $repository->getRoutesWithParametersQueryBuilder();
                },
                'property'      => 'route',
                'placeholder'   => 'Route'
            ])
            ->add('type', 'wucdbm_filter_entity', [
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
