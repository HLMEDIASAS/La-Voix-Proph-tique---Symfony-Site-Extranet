<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use biyn\lvpBundle\Form\Type\CoursType;
use biyn\lvpBundle\Entity\Cours;
use biyn\lvpBundle\Service\Email;

class AdminController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function membresAction()
    {
        # Récupération de la liste des Membres
        $membresRepository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('biynlvpBundle:Membres');
        
        $membres = $membresRepository->findAll();       
        
        # Affichage et Transmission à la Vue
        return $this->render('biynlvpBundle:Admin:membres.html.twig', ['membres' => $membres]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function nouveaucourAction(Request $request)
    {
        
        # Création d'un Nouveau Cour
        $cour = new Cours();
        
        # Création du Formulaire
        $form = $this->createForm(CoursType::class, $cour);
        
        # Traitement de la Requète
        $form->handleRequest($request);
         
        # Traitement POST
        if ($form->isSubmitted() && $form->isValid()) :
            
            # Récupération du Fichier Uploadé
            $file = $cour->getMp3();
            
            # Génération d'un nouveau nom de fichier unique
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            
            # Déplacement du fichier mp3 dans le dossier /sounds/
            $file->move(
                $this->getParameter('cour_directory'),
                $fileName
                );
            
            # Mise à jour du fichier mp3 dans l'objet Cour
            $cour->setMp3($fileName);
             
            # Enregistrement en BDD
            $em = $this->getDoctrine()->getManager();
            $em->persist($cour);
            $em->flush();
            
            # Récupération de la liste des Membres pour notification
            $membresRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('biynlvpBundle:Membres');
            
            $membres = $membresRepository->findAll();
             
            # Envoi d'un Email aux membres
            $Email = $this->get(Email::class);
            $Email->sendNouveauCourMessage($membres, $cour);
            
            # Envoi d'une Notification
            $session = $request->getSession();
            $session->getFlashBag()->add('notam', 'Merci, votre cour a bien été ajouté.');
             
            # Redirection vers la Page de Gestion des Membres
            return $this->redirectToRoute('biynlvp_espacemembres');
         
        endif;
        
        # Affichage et Transmission du Formulaire à la Vue
        return $this->render('biynlvpBundle:Admin:nouveaucour.html.twig', ['form' => $form->createView()]);
    }
    
}










