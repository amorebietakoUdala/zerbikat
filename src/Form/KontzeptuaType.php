<?php

namespace App\Form;

use App\Entity\Kontzeptua;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class KontzeptuaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('kodea')
            ->add('kontzeptuaeu')
            ->add('kontzeptuaes')
            ->add('kopurua')
            ->add('unitatea')
//            ->add('createdAt',DatetimeType::class, array('widget' => 'single_text'))
//            ->add('updatedAt',DatetimeType::class, array('widget' => 'single_text'))
            ->add('udala')
            ->add('kontzeptumota')
            ->add('baldintza')
            ->add('azpiatala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kontzeptua::class
        ]);
    }
}
