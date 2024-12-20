<?php

namespace App\Form;

use App\Entity\Dokumentazioa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DokumentazioaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kodea')
            ->add('deskribapenaeu')
            ->add('deskribapenaes')
            ->add('estekaeu')
            ->add('estekaes')
            ->add('udala')
            ->add('dokumentumota')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Dokumentazioa::class
        ]);
    }
}
