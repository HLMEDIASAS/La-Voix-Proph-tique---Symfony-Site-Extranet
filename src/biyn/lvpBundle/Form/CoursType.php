<?php

namespace biyn\lvpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CoursType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class, [
                         'required'   => true,
                         'label'      => false,
                          'attr' => [
                                'placeholder' => 'Titre...'
                           ]
                     ])
                     ->add('mp3', FileType::class, [
                            'label' => 'Fichier Audio (MPEG / MP3)',
                          'attr' => [
                                'class' => 'dropify'
                           ]
                         ])
                     ->add('submit', SubmitType::class, ['label' => 'Ajouter le Cour']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'biyn\lvpBundle\Entity\Cours'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'biyn_lvpbundle_cours';
    }


}
