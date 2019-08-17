<?php

namespace MicroBundle\Form;

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

        $choices = ['abc' => 'abc',
            'dfddf' => 'ddd'
        ];
        $builder->add('showTables')
            ->add('showBuildingData')
            ->add('showClientData')
            ->add('showStamp')
            ->add('inspectors', ChoiceType::class, [

                // uses the User.username property as the visible option string
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
