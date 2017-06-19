<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use biyn\lvpBundle\Entity\Membres;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class MembreController extends Controller
{
    public function accueilAction()
    {
        # Récupération du Repository
        $coursRepository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('biynlvpBundle:Cours');
        
        $cours = $coursRepository->findAll();        
        
        return $this->render('biynlvpBundle:Membre:accueil.html.twig', ['cours' => $cours]);
    }
    
    public function connexionAction()
    {
        return $this->render('biynlvpBundle:Membre:connexion.html.twig');
    }
    
    public function inscriptionAction(Request $request)
    {
        # Création d'un Objet Membre
        $membre = new Membres();
        
        # Création du Formulaire pour l'inscription
        $form = $this->createFormBuilder($membre)
                     ->add('prenom', TextType::class, [
                         'required'   => true,
                         'empty_data' => 'Prénom...',
                     ])
                     ->add('nom', TextType::class, [
                         'required'   => true,
                         'empty_data' => 'Nom...',
                     ])
                     ->add('email', EmailType::class, [
                         'required'   => true,
                         'empty_data' => 'Email...',
                     ])
                     ->add('isadmin', CheckboxType::class, [
                         'label' => 'Administrateur ?',
                         'required' => false
                     ])
                     ->add('submit', SubmitType::class, ['label' => 'Inscrire ce membre'])
                     ->getForm();
         
         # Traitement de la Requète
         $form->handleRequest($request);
         
         # Traitement POST
         if ($form->isSubmitted() && $form->isValid()) :
             
             # Récupération du Membre
             $data = $form->getData();
         
             # Enregistrement en BDD
             $em = $this->getDoctrine()->getManager();
             $em->persist($data);
             $em->flush();
             
             $request->getSession()->getFlashBag()->add('notice', 'Membre bien ajouté.');
             
             // On redirige vers la page de visualisation de l'annonce nouvellement créée         
             return $this->redirectToRoute('biynlvp_admin_membres');
             
         endif;
        
        # Passage à la Vue
        return $this->render('biynlvpBundle:Membre:inscription.html.twig', 
                        ['form' => $form->createView(), 'membre' => $membre]);
        
    }
    
}










