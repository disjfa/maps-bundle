<?php

namespace Disjfa\MapsBundle\Form\Type;

use Disjfa\MapsBundle\Entity\Map;
use Disjfa\MapsBundle\Entity\MapMarker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class MapMarkerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ]
        ]);

        $builder->add('map', EntityType::class, [
            'class' => Map::class,
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ]
        ]);

        $builder->add('centerLat', NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ]
        ]);

        $builder->add('centerLng', NumberType::class, [
            'constraints' => [
                new NotBlank(),
                new NotNull(),
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MapMarker::class,
            'csrf_protection' => false,
        ]);
    }
}
