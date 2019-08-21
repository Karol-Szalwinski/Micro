<?php

namespace MicroBundle\Form;

use MicroBundle\Entity\DocumentInspector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use MicroBundle\Entity\Inspector;


class FireInspectionType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array('3 miesiące' => '3', '6 miesięcy' => '6', '1 rok' => '12');

        $builder->add('inspectionDate', DateType::class, ['widget' => 'single_text', 'html5' => false,])
            ->add('nextInspectionForMonth', ChoiceType::class, array(
                'choices' => $choices,
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Ręcznie',
                ))
            ->add('nextInspectionDate', DateType::class, ['widget' => 'single_text', 'html5' => false,])
            ->add('scope')
            ->add('tempInspectors', EntityType::class, [// looks for choices from this entity
                'class' => Inspector::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'fullname',

                // used to render a select box, check boxes or radios
                'multiple' => true, 'expanded' => true,])
            ->add('deviceShortlistPosition')
            ->add('legal');

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'MicroBundle\Entity\Document'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_fireinspection';
    }


}
