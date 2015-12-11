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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\BaseFilterType;

class RouteFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'Name'
            ])
            ->add('route', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'Route'
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Filter\Route\RouteFilter'
        ));
    }

}
