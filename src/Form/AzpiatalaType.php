<?php

namespace App\Form;

use App\Entity\Azpiatala;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AzpiatalaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('kodea')
            ->add('izenburuaeu')
            ->add('izenburuaes')
//            ->add('createdAt',DatetimeType::class, array('widget' => 'single_text'))
//            ->add('updatedAt',DatetimeType::class, array('widget' => 'single_text'))
            ->add('udala')
//            ->add('atala')
//            ->add('fitxak')
            ->add('kontzeptuak', CollectionType::class, ['entry_type' => KontzeptuaType::class, 'allow_add' => true, 'allow_delete' => true, 'prototype' => true, 'by_reference' => false])
            ->add('parrafoak', CollectionType::class, ['entry_type' => AzpiatalaparrafoaType::class, 'allow_add' => true, 'allow_delete' => true, 'prototype' => true, 'by_reference' => false])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Azpiatala::class
        ]);
    }
}
