<?php

namespace App\Form;

use App\Entity\Ordenantzaparrafoa;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenantzaparrafoaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordena')
//            ->add('testuaeu')
            ->add('testuaeu',CKEditorType::class, ['config' => []])
            ->add('testuaes',CKEditorType::class, ['config' => []])
//            ->add('testuaes')
//            ->add('createdAt', 'datetime')
//            ->add('updatedAt', 'datetime')
            ->add('udala')
            ->add('ordenantza')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ordenantzaparrafoa::class
        ]);
    }
}
