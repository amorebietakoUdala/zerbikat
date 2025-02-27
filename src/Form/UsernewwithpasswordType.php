<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class UsernewwithpasswordType extends AbstractType
//class UserType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username')
            ->add('udala')
            ->add('azpisaila')
            ->add('enabled')
            ->add('email', EmailType::class, ['required' => true])
//            ->add('roles')
//            ->add('password')
            ->add('roles',  ChoiceType::class, [
                'multiple' => true, 
                'choices'  => [
                    'Admin' => 'ROLE_ADMIN', 
                    'Kudeaketa' => 'ROLE_KUDEAKETA', 
                    'Erabiltzailea' => 'ROLE_USER'
                    ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class, 
                'first_options'  => [
                    'label' => 'messages.pasahitza',
                    'translation_domain' => 'messages'
                ], 
                'second_options' => [
                    'label' => 'messages.pasahitzaerrepikatu', 
                    'translation_domain' => 'messages'
                ]
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
