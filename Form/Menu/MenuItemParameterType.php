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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItem;
use Wucdbm\Bundle\MenuBuilderBundle\Repository\RouteParameterRepository;

class MenuItemParameterType extends AbstractType {

    /** @var MenuItem */
    protected $item;

    public function __construct(MenuItem $item) {
        $this->item = $item;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $item = $this->item;
        $builder
            ->add('value', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label' => 'Value'
            ])
            ->add('parameter', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'         => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\RouteParameter',
                'property'      => 'parameter',
                'query_builder' => function (RouteParameterRepository $repository) use ($item) {
                    $route = $item->getRoute();

                    return $repository->getParametersByRouteQueryBuilder($route);
                },
                'constraints'   => [
                    new NotBlank([
                        'message' => 'This value is required'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter',
            'label' => false
        ]);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item_parameter';
    }
}