<?php

    namespace App\Form;

use App\Entity\Fitxa;
use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Ivory\CKEditorBundle\Form\Type\CKEditorType;
//use App\Entity\FitxaProzedura;
    use Symfony\Component\Form\Extension\Core\Type\CollectionType;

    class FitxanewType extends AbstractType
    {

        /**
         * @param FormBuilderInterface $builder
         * @param array $options
         */
        public function buildForm ( FormBuilderInterface $builder, array $options )
        {
            $builder
                ->add( 'espedientekodea' )
                ->add( 'deskribapenaeu' )
                ->add( 'deskribapenaes' )

            ;
        }

        /**
         * @param OptionsResolver $resolver
         */
        public function configureOptions ( OptionsResolver $resolver )
        {
            $resolver->setDefaults([
                'data_class' => Fitxa::class
            ]);
        }
    }
