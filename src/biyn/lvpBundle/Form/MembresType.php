<?php

namespace biyn\lvpBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
# Chargement des Types
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MembresType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prenom', TextType::class, [
                         'required'   => true,
                         'label'      => false,
                          'attr' => [
                                'placeholder' => 'Prénom...',
                           ]
                     ])
                     ->add('nom', TextType::class, [
                         'required'   => true,
                         'label'      => false,
                          'attr' => [
                                'placeholder' => 'Nom...',
                           ]
                     ])
                     ->add('email', EmailType::class, [
                         'required'   => true,
                         'label'      => false,
                          'attr' => [
                                'placeholder' => 'Email...',
                           ]
                     ])
                     ->add(
                            'roles', ChoiceType::class, [
                                'choices'   => ['Membre' => 'ROLE_MEMBRE', 'Administrateur' => 'ROLE_ADMIN'],
                                'expanded'  => true,
                                'multiple'  => false,
                                'label'     => 'Type d\'accès',
                                'data' => 'ROLE_MEMBRE'
                            ]
                        )
                     ->add('submit', SubmitType::class, ['label' => 'Inscrire ce membre']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'biyn\lvpBundle\Entity\Membres'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'biyn_lvpbundle_membres';
    }


}
