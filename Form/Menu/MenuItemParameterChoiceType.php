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
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemParameterChoiceType extends AbstractType {

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'label'              => 'Choices',
            'placeholder'        => 'Choices',
            'invalid_message'    => 'Invalid Choice',
            'expanded'           => false,
            'multiple'           => false,
            'choices_as_values'  => true,
            'translation_domain' => 'messages',
            'choice_label'       => function ($allChoices) {
                return $allChoices;
            },
            'choice_value'       => function ($allChoices) {
                return $allChoices;
            }
        ]);
    }

    public function getParent() {
        return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item_parameter_choice';
    }
}