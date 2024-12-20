<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AMREU\UserBundle\Form\UserType as BaseUserType;
use App\Entity\Udala;
use App\Repository\UdalaRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserType extends BaseUserType
//class UserType extends BaseType
{
    private $class;
    private $allowedRoles;

    public function __construct($class, $allowedRoles)
    {
        $this->class = $class;
        if (empty($allowedRoles)) {
            $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN'];
        }
        foreach ($allowedRoles as $role) {
            $this->allowedRoles[$role] = $role;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $readonly = $options['readonly'];
        $password_change = $options['password_change'];
        $builder->add('azpisaila', null, [
            'label' => 'user.azpisaila',
            'translation_domain' => 'user_bundle',
            'disabled' => $readonly,
            'required' => false,
        ])
        ->add('activated', CheckboxType::class, [
            'label' => 'user.activated',
            'translation_domain' => 'user_bundle',
            'disabled' => $readonly,
            'required' => false,
        ])
        ->add('roles', ChoiceType::class, [
            'label' => 'user.roles',
            'choices' => $this->allowedRoles,
            'multiple' => true,
            'expanded' => false,
            'required' => true,
            'translation_domain' => 'user_bundle',
            'disabled' => $readonly,
        ])
        ;
        $builder
        ->add('udala', EntityType::class, [
            'class' => Udala::class,
            'label' => 'user.udala',
            'translation_domain' => 'user_bundle',
            'disabled' => $readonly,
            'query_builder' => function (UdalaRepository $repo) {
                return $repo->findOrderedByIdDesc();
            },
        ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'password_change' => false,
            'readonly' => false,
            'new' => false,
        ]);
    }
}
