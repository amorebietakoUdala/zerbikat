<?php

namespace App\Form;

use App\Entity\Besteak2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Besteak2Type extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('izenburuaeu')
            ->add('izenburuaes')
            ->add('estekaeu')
            ->add('estekaes')
            ->add('kodea')
            ->add('udala')
//            ->add('fitxak')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Besteak2::class
        ]);
    }
}
