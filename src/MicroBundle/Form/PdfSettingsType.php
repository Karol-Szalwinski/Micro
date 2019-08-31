<?php

namespace MicroBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PdfSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $inspectors = $options['inspectors'];
        $stamps = $options['stamps'];

        $builder->add('showTables')
            ->add('showBuildingData')
            ->add('showClientData')
            ->add('showStamp')
            ->add('stamps', EntityType::class, [

                'class' => 'MicroBundle:Stamp',
                'choices' => $stamps,
                'choice_label' => 'imageView',
                'multiple' => true,
                'expanded' => true,])
            ->add('inspectors', ChoiceType::class, [
                'choices' => $inspectors,
                'multiple' => true,
                'expanded' => true,])


        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MicroBundle\Entity\PdfSettings',
            'inspectors' => [],
            'stamps' => [],

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_pdfsettings';
    }


}
