<?php

namespace App\Form;

use App\Entity\Kanala;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KanalaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('izenaeu')
            ->add('izenaes')
            ->add('deskribapenaeu')
            ->add('deskribapenaes')
            ->add('estekaeu')
            ->add('estekaes')
            ->add('telefonoa')
            ->add('fax')
            ->add('kalezbkia')
            ->add('postakodea')
            ->add('ordutegia')
            ->add('telematikoa')
            ->add('udala')
            ->add('kanalmota')
            ->add('kalea')
            ->add('eraikina')
            ->add('barrutia')
            ->add('erakutsi')
//            ->add('fitxak')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kanala::class
        ]);
    }
}
