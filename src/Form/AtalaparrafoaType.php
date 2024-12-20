<?php

namespace App\Form;

use App\Entity\Atalaparrafoa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtalaparrafoaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordena')
            ->add('testuaeu')
            ->add('testuaes')
//            ->add('createdAt',DatetimeType::class, array('widget' => 'single_text'))
//            ->add('updatedAt',DatetimeType::class, array('widget' => 'single_text'))
            ->add('udala')
            ->add('atala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Atalaparrafoa::class
        ]);
    }
}
