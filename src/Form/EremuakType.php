<?php

namespace App\Form;

use App\Entity\Eremuak;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EremuakType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) :void
    {
        $builder
            ->add('oharraktext',CheckboxType::class, ['label'    => 'messages.oharraktext', 'translation_domain' => 'messages'])
            ->add('oharraklabeleu',TextType::class, ['label' => 'messages.oharraklabeleu', 'translation_domain' => 'messages'])
            ->add('oharraklabeles',TextType::class, ['label' => 'messages.oharraklabeles', 'translation_domain' => 'messages'])
            ->add('helburuatext',CheckboxType::class, ['label'    => 'messages.helburuatext', 'translation_domain' => 'messages'])
            ->add('helburualabeleu',TextType::class, ['label' => 'messages.helburualabeleu', 'translation_domain' => 'messages'])
            ->add('helburualabeles',TextType::class, ['label' => 'messages.helburualabeles', 'translation_domain' => 'messages'])
            ->add('ebazpensinpli',CheckboxType::class, ['label'    => 'messages.ebazpensinpli', 'translation_domain' => 'messages'])
            ->add('ebazpensinplilabeleu',TextType::class, ['label' => 'messages.ebazpensinplilabeleu', 'translation_domain' => 'messages'])
            ->add('ebazpensinplilabeles',TextType::class, ['label' => 'messages.ebazpensinplilabeles', 'translation_domain' => 'messages'])
            ->add('arduraaitorpena',CheckboxType::class, ['label'    => 'messages.arduraaitorpena', 'translation_domain' => 'messages'])
            ->add('arduraaitorpenalabeleu',TextType::class, ['label' => 'messages.arduraaitorpenalabeleu', 'translation_domain' => 'messages'])
            ->add('arduraaitorpenalabeles',TextType::class, ['label' => 'messages.arduraaitorpenalabeles', 'translation_domain' => 'messages'])
            ->add('aurreikusi',CheckboxType::class, ['label'    => 'messages.aurreikusi', 'translation_domain' => 'messages'])
            ->add('aurreikusilabeleu',TextType::class, ['label' => 'messages.aurreikusilabeleu', 'translation_domain' => 'messages'])
            ->add('aurreikusilabeles',TextType::class, ['label' => 'messages.aurreikusilabeles', 'translation_domain' => 'messages'])
            ->add('arrunta',CheckboxType::class, ['label'    => 'messages.arrunta', 'translation_domain' => 'messages'])
            ->add('arruntalabeleu',TextType::class, ['label' => 'messages.arruntalabeleu', 'translation_domain' => 'messages'])
            ->add('arruntalabeles',TextType::class, ['label' => 'messages.arruntalabeles', 'translation_domain' => 'messages'])
            ->add('isiltasunadmin',CheckboxType::class, ['label'    => 'messages.isiltasunadmin', 'translation_domain' => 'messages'])
            ->add('isiltasunadminlabeleu',TextType::class, ['label' => 'messages.isiltasunadminlabeleu', 'translation_domain' => 'messages'])
            ->add('isiltasunadminlabeles',TextType::class, ['label' => 'messages.isiltasunadminlabeles', 'translation_domain' => 'messages'])
            ->add('norkeskatutext',CheckboxType::class, ['label'    => 'messages.norkeskatutext', 'translation_domain' => 'messages'])
            ->add('norkeskatutable',CheckboxType::class, ['label'    => 'messages.norkeskatutable', 'translation_domain' => 'messages'])
            ->add('norkeskatulabeleu',TextType::class, ['label' => 'messages.norkeskatulabeleu', 'translation_domain' => 'messages'])
            ->add('norkeskatulabeles',TextType::class, ['label' => 'messages.norkeskatulabeles', 'translation_domain' => 'messages'])
            ->add('dokumentazioatext',CheckboxType::class, ['label'    => 'messages.dokumentazioatext', 'translation_domain' => 'messages'])
            ->add('dokumentazioatable',CheckboxType::class, ['label'    => 'messages.dokumentazioatable', 'translation_domain' => 'messages'])
            ->add('dokumentazioalabeleu',TextType::class, ['label' => 'messages.dokumentazioalabeleu', 'translation_domain' => 'messages'])
            ->add('dokumentazioalabeles',TextType::class, ['label' => 'messages.dokumentazioalabeles', 'translation_domain' => 'messages'])
            ->add('kostuatext',CheckboxType::class, ['label'    => 'messages.kostuatext', 'translation_domain' => 'messages'])
            ->add('kostuatable',CheckboxType::class, ['label'    => 'messages.kostuatable', 'translation_domain' => 'messages'])
            ->add('kostualabeleu',TextType::class, ['label' => 'messages.kostualabeleu', 'translation_domain' => 'messages'])
            ->add('kostualabeles',TextType::class, ['label' => 'messages.kostualabeles', 'translation_domain' => 'messages'])
            ->add('araudiatext',CheckboxType::class, ['label'    => 'messages.araudiatext', 'translation_domain' => 'messages'])
            ->add('araudiatable',CheckboxType::class, ['label'    => 'messages.araudiatable', 'translation_domain' => 'messages'])
            ->add('araudialabeleu',TextType::class, ['label' => 'messages.araudialabeleu', 'translation_domain' => 'messages'])
            ->add('araudialabeles',TextType::class, ['label' => 'messages.araudialabeles', 'translation_domain' => 'messages'])
            ->add('prozeduratext',CheckboxType::class, ['label'    => 'messages.prozeduratext', 'translation_domain' => 'messages'])
            ->add('prozeduratable',CheckboxType::class, ['label'    => 'messages.prozeduratable', 'translation_domain' => 'messages'])
            ->add('prozeduralabeleu',TextType::class, ['label' => 'messages.prozeduralabeleu', 'translation_domain' => 'messages'])
            ->add('prozeduralabeles',TextType::class, ['label' => 'messages.prozeduralabeles', 'translation_domain' => 'messages'])
            ->add('doklaguntext',CheckboxType::class, ['label'    => 'messages.doklaguntext', 'translation_domain' => 'messages'])
            ->add('doklaguntable',CheckboxType::class, ['label'    => 'messages.doklaguntable', 'translation_domain' => 'messages'])
            ->add('doklagunlabeleu',TextType::class, ['label' => 'messages.doklagunlabeleu', 'translation_domain' => 'messages'])
            ->add('doklagunlabeles',TextType::class, ['label' => 'messages.doklagunlabeles', 'translation_domain' => 'messages'])
            ->add('datuenbabesatext',CheckboxType::class, ['label'    => 'messages.datuenbabesatext', 'translation_domain' => 'messages'])
            ->add('datuenbabesatable',CheckboxType::class, ['label'    => 'messages.datuenbabesatable', 'translation_domain' => 'messages'])
            ->add('datuenbabesalabeleu',TextType::class, ['label' => 'messages.datuenbabesalabeleu', 'translation_domain' => 'messages'])
            ->add('datuenbabesalabeles',TextType::class, ['label' => 'messages.datuenbabesalabeles', 'translation_domain' => 'messages'])
            ->add('azpisailatable',CheckboxType::class, ['label'    => 'messages.azpisailatable', 'translation_domain' => 'messages'])
            ->add('azpisailalabeleu',TextType::class, ['label' => 'messages.azpisailalabeleu', 'translation_domain' => 'messages'])
            ->add('azpisailalabeles',TextType::class, ['label' => 'messages.azpisailalabeles', 'translation_domain' => 'messages'])
            ->add('norkebatzitext',CheckboxType::class, ['label'    => 'messages.norkebatzitext', 'translation_domain' => 'messages'])
            ->add('norkebatzitable',CheckboxType::class, ['label'    => 'messages.norkebatzitable', 'translation_domain' => 'messages'])
            ->add('norkebatzilabeleu',TextType::class, ['label' => 'messages.norkebatzilabeleu', 'translation_domain' => 'messages'])
            ->add('norkebatzilabeles',TextType::class, ['label' => 'messages.norkebatzilabeles', 'translation_domain' => 'messages'])
            ->add('besteak1text',CheckboxType::class, ['label'    => 'messages.besteak1text', 'translation_domain' => 'messages'])
            ->add('besteak1table',CheckboxType::class, ['label'    => 'messages.besteak1table', 'translation_domain' => 'messages'])
            ->add('besteak1labeleu',TextType::class, ['label' => 'messages.besteak1labeleu', 'translation_domain' => 'messages'])
            ->add('besteak1labeles',TextType::class, ['label' => 'messages.besteak1labeles', 'translation_domain' => 'messages'])
            ->add('besteak2text',CheckboxType::class, ['label'    => 'messages.besteak2text', 'translation_domain' => 'messages'])
            ->add('besteak2table',CheckboxType::class, ['label'    => 'messages.besteak2table', 'translation_domain' => 'messages'])
            ->add('besteak2labeleu',TextType::class, ['label' => 'messages.besteak2labeleu', 'translation_domain' => 'messages'])
            ->add('besteak2labeles',TextType::class, ['label' => 'messages.besteak2labeles', 'translation_domain' => 'messages'])
            ->add('besteak3text',CheckboxType::class, ['label'    => 'messages.besteak3text', 'translation_domain' => 'messages'])
            ->add('besteak3table',CheckboxType::class, ['label'    => 'messages.besteak3table', 'translation_domain' => 'messages'])
            ->add('besteak3labeleu',TextType::class, ['label' => 'messages.besteak3labeleu', 'translation_domain' => 'messages'])
            ->add('besteak3labeles',TextType::class, ['label' => 'messages.besteak3labeles', 'translation_domain' => 'messages'])
            ->add('kanalatext',CheckboxType::class, ['label'    => 'messages.kanalatext', 'translation_domain' => 'messages'])
            ->add('kanalatable',CheckboxType::class, ['label'    => 'messages.kanalatable', 'translation_domain' => 'messages'])
            ->add('kanalalabeleu',TextType::class, ['label' => 'messages.kanalalabeleu', 'translation_domain' => 'messages'])
            ->add('kanalalabeles',TextType::class, ['label' => 'messages.kanalalabeles', 'translation_domain' => 'messages'])
            ->add('epealabeleu',TextType::class, ['label' => 'messages.epealabeleu', 'translation_domain' => 'messages'])
            ->add('epealabeles',TextType::class, ['label' => 'messages.epealabeles', 'translation_domain' => 'messages'])
            ->add('doanlabeleu',TextType::class, ['label' => 'messages.doanlabeleu', 'translation_domain' => 'messages'])
            ->add('doanlabeles',TextType::class, ['label' => 'messages.doanlabeles', 'translation_domain' => 'messages'])
//            ->add('udala',TextType::class, array(
//                'label'    => 'messages.udala',
//                'translation_domain' => 'messages',
//            ))
            ->add('udala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eremuak::class
        ]);
    }
}
