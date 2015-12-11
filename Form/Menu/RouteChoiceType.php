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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RouteChoiceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('route', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'       => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\Route',
                'property'    => 'route',
                'placeholder' => 'Route',
                'attr'        => [
                    'class' => 'select2'
                ]
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem'
        ]);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item_route_choice_type';
    }
}