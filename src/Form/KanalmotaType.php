<?php

namespace App\Form;

use App\Entity\Kanalmota;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KanalmotaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('motaeu')
            ->add('motaes')
            ->add('esteka')
//            ->add('ikonoa')
            ->add('udala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kanalmota::class
        ]);
    }
}
