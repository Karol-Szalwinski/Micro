<?php

namespace MicroBundle\Form;

use MicroBundle\Entity\DocumentInspector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MicroBundle\Entity\Inspector;


class FireInspectionSummaryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'System jest sprawny' => 'System jest sprawny',
            'System jest niesprawny' => 'System jest niesprawny',
            'System jest w niepełnej sprawności' => 'System jest w niepełnej sprawności'
        );
        $builder
            ->add('conclusion', ChoiceType::class, array(
                'choices' => $choices,
                'required' => false,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('comment')
            ->add('recomendations')
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MicroBundle\Entity\FireInspection'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_fireinspection';
    }


}
