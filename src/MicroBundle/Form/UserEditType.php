<?php

namespace MicroBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
            'Użytkownik |' => 'ROLE_USER',
            'Administrator ' => 'ROLE_ADMIN'
        );

        $yesNo = array(
            'Tak ' => 1 ,
            'Nie ' => 0
        );

        $builder
            ->add('email', EmailType::class, array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('roles', ChoiceType::class, array(
                    'label' => 'Roles',
                    'choices' => $permissions,
                    'multiple' => true,
                    'expanded' => true
                )
            )
            ->add('name')
            ->add('surname')
            ->add('enabled', ChoiceType::class, array(
                    'choices' => $yesNo,
                    'multiple' => false,
                    'expanded' => false
                )
            )
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MicroBundle\Entity\User',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_user';
    }


}
