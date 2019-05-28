<?php

namespace MicroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FireProtectionDeviceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class, [
                'class'=>'MicroBundle\Entity\DeviceName',
                'choice_label'=>'name',
            ])
            ->add('shortname', EntityType::class, [
                'class'=>'MicroBundle\Entity\DeviceName',
                'choice_label'=>'shortname',
            ])
            ->add('loopNo')
            ->add('number');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MicroBundle\Entity\FireProtectionDevice'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_fireprotectiondevice';
    }


}
