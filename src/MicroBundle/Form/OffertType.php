<?php

namespace MicroBundle\Form;

use MicroBundle\Entity\OffService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffertType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name')

            ->add('clients', CollectionType::class, [
                'entry_type' => OffertClientType::class,
                'entry_options' => ['label' => true],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false
            ])
            ->add('myCompany')
            ->add('addDate', DateType::class, ['widget' => 'single_text', 'html5' => false,])
            ->add('expireDate', DateType::class, ['widget' => 'single_text', 'html5' => false,])
            ->add('offPositions', CollectionType::class, [
                'entry_type' => OffPositionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false
            ])
            ->add('offServices', CollectionType::class, [
                'entry_type' => OffServiceType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false
            ])
            ->add('comment')
            ->add('totalValue', HiddenType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MicroBundle\Entity\Offert'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'microbundle_offert';
    }


}
