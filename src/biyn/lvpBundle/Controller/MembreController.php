<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use biyn\lvpBundle\Entity\Membres;
use Symfony\Component\HttpFoundation\Request;
use biyn\lvpBundle\Form\MembresType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MembreController extends Controller
{
    
    /**
     * @Security("has_role('ROLE_MEMBRE')")
     */
    public function accueilAction()
    {
        # Récupération du Repository
        $coursRepository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('biynlvpBundle:Cours');
        
        # Récupération des cours
        $cours = $coursRepository->findAll();        
        
        # Envoi des informations à la vue
        return $this->render('biynlvpBundle:Membre:accueil.html.twig', ['cours' => $cours]);
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function inscriptionAction(Request $request)
    {
        # Création d'un Objet Membre
        $membre = new Membres();
        
        # Création du Formulaire pour l'inscription
        $form = $this->createForm(MembresType::class);
         
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
             
             //$request->getSession()->getFlashBag()->add('notice', 'Membre bien ajouté.');
             
             # Redirection vers la Page de Gestion des Membres      
             return $this->redirectToRoute('biynlvp_admin_membres');
             
         endif;
        
        # Passage à la Vue
        return $this->render('biynlvpBundle:Membre:inscription.html.twig', 
                        ['form' => $form->createView()]);
        
    }
    
}