<?php

namespace MicroBundle\Form;

use MicroBundle\Entity\Parameter;
use MicroBundle\Entity\ProductParameter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6 d-inline',
                    'readonly' => true],
                'label' => false,
            ])
            ->add('value', TextType::class, [
                'attr' => ['class' => 'form-control col-md-6 d-inline'],
                    'label' => false

                ]

            )
            ->add('prototypeId', HiddenType::class, []);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProductParameter::class,]);
    }
}