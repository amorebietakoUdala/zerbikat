<?php

namespace App\Form;

use App\Entity\Atala;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AtalaType extends AbstractType
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
            ->add('ordenantza')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Atala::class
        ]);
    }
}
