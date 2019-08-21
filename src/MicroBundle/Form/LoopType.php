<?php

namespace MicroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoopType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('loop', IntegerType::class, array(
                'disabled' => true,
                'data' => $options['data']['loop']
            ))
            ->add('quantityDevices',IntegerType::class, array(
                'attr' => [
                    'min' => 1,
                    'max' => 120
                ]
            ));
    }


}
