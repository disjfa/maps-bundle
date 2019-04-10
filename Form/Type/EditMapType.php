<?php

namespace Disjfa\MapsBundle\Form\Type;

use Disjfa\MapsBundle\Entity\Map;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class EditMapType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('centerLat', NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ],
        ]);
        $builder->add('centerLng', NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ],
        ]);
        $builder->add('zoom', NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ],
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Map::class,
            'csrf_protection' => false,
        ]);
    }
}
