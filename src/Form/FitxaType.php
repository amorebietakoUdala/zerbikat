<?php

    namespace App\Form;

use App\Entity\Azpiatala;
use App\Entity\Besteak1;
use App\Entity\Besteak2;
use App\Entity\Besteak3;
use App\Entity\Doklagun;
use App\Entity\Dokumentazioa;
use App\Entity\Etiketa;
use App\Entity\Fitxa;
use App\Entity\Kanala;
use App\Entity\Norkeskatu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FitxaType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm ( FormBuilderInterface $builder, array $options ): void
    {
        $udala = null;
        /** We are editing and udala check is udala is set */
        $data = $options['data'];
        $locale = $options['locale'];
        if ( null !== $data ) {
            $udala = $data->getUdala();
        }
        $user = $options['user'];
        $api_url = $options[ 'api_url' ];

        $builder
            ->add( 'espedientekodea' )
            ->add( 'expedientes' )
            ->add( 'deskribapenaeu' )
            ->add( 'deskribapenaes' )
            ->add(
                'helburuaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'helburuaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'norkeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'norkes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'dokumentazioaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'dokumentazioaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'kostuaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'kostuaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add( 'ebazpensinpli' )
            ->add( 'arduraaitorpena' )
            ->add( 'isiltasunadmin' )
            ->add(
                'araudiaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'araudiaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'prozeduraeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'prozeduraes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'doklaguneu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'doklagunes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'oharrakeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'oharrakes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'jarraibideakeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'jarraibideakes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add( 'publikoa' )
            ->add( 'hitzarmena' )
            ->add( 'kontsultak' )
            ->add( 'parametroa' )
            ->add( 'createdAt', DatetimeType::class, ['widget' => 'single_text'] )
            ->add( 'updatedAt', DatetimeType::class, ['widget' => 'single_text'] )
            ->add(
                'besteak1eu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'besteak1es',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'besteak2eu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'besteak2es',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'besteak3eu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'besteak3es',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'datuenbabesaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'datuenbabesaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'norkonartueu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'norkonartues',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'kanalaeu',
                CKEditorType::class,
                ['config' => []]
            )
            ->add(
                'kanalaes',
                CKEditorType::class,
                ['config' => []]
            )
            ->add( 'udala' )
            ->add( 'norkebatzi' )
            ->add( 'zerbitzua' )
            ->add( 'datuenbabesa' )
            ->add( 'azpisaila' )
            ->add( 'aurreikusi' )
            ->add( 'arrunta' )
            ->add(
                'dokumentazioak',
                EntityType::class,
                ['class'       => Dokumentazioa::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu dokumentuak', 'group_by'    => 'dokumentumota']
            )
            ->add(
                'etiketak',
                EntityType::class,
                ['class'       => Etiketa::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu etiketak', 'empty_data'  => []]
            )
            ->add(
                'kanalak',
                EntityType::class,
                ['class'       => Kanala::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu kanalak', 'group_by'    => 'kanalmota']
            )
            ->add(
                'besteak1ak',
                EntityType::class,
                ['class'       => Besteak1::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu besteak1']
            )
            ->add(
                'besteak2ak',
                EntityType::class,
                ['class'       => Besteak2::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu besteak2']
            )
            ->add(
                'besteak3ak',
                EntityType::class,
                ['class'       => Besteak3::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu besteak3']
            )
            ->add(
                'norkeskatuak',
                EntityType::class,
                ['class'       => Norkeskatu::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu nork eska dezakeen']
            )
            ->add(
                'doklagunak',
                EntityType::class,
                ['class'       => Doklagun::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu dokumentazio lagungarria']
            )
            ->add(
                'azpiatalak',
                EntityType::class,
                ['class'       => Azpiatala::class, 'required'    => false, 'multiple'    => 'multiple', 'placeholder' => 'Aukeratu kostu taulak']
            )
            ->add(
                'prozedurak',
                CollectionType::class,
                ['entry_type'   => FitxaProzeduraType::class, 'allow_add'    => true, 'allow_delete' => true, 'by_reference' => false]
            )
            ->add(
                'araudiak',
                CollectionType::class,
                ['entry_type'   => FitxaAraudiaType::class, 'allow_add'    => true, 'allow_delete' => true, 'by_reference' => false]
            )
            ->add(
                'kostuak',
                CollectionType::class, [
                    'entry_type'   => FitxaKostuaType::class, 
                    'entry_options'  => [
                        'udala' => $user->getUdala() !== null ? $user->getUdala()->getId() : $udala, 
                        'api_url' => $api_url,
                        'locale' => $locale
                    ], 
                    'allow_add'    => true, 
                    'allow_delete' => true, 
                    'by_reference' => false
                ]
            )

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions ( OptionsResolver $resolver ): void
    {
        $resolver->setDefaults([
            'data_class' => Fitxa::class, 
            'user' => null, 
            'api_url' => null,
            'locale' => 'eu'   
        ]);
    }
}
