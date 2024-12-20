<?php

namespace App\Form;

use App\Entity\Zerbitzua;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class ZerbitzuaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kodea',TextType::class, [
                'label' => 'messages.kodea', 
                'translation_domain' => 'messages'
            ])
            ->add('zerbitzuaeu',TextType::class, [
                'label' => 'messages.zerbitzua', 
                'translation_domain' => 'messages'
            ])
            ->add('zerbitzuaes',TextType::class, [
                'label' => 'messages.zerbitzua', 
                'translation_domain' => 'messages'
            ])
            ->add('erroaeu',TextType::class, [
                'label' => 'messages.erroa', 
                'translation_domain' => 'messages'
            ])
            ->add('erroaes',TextType::class, [
                'label' => 'messages.erroa', 
                'translation_domain' => 'messages'
            ])
//            ->add('espedientekudeaketa')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zerbitzua::class
        ]);
    }
}
