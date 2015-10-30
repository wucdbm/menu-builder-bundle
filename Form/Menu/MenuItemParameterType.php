<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Form\Menu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('value', 'text', [
                'label' => 'Value'
            ])
            ->add('parameter', 'entity', [
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

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\MenuBuilderBundle\Entity\MenuItemParameter'
        ]);
    }

    public function getName() {
        return 'wucdbm_menu_builder_menu_item_parameter';
    }
}