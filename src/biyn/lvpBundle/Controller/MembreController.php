<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use biyn\lvpBundle\Entity\Membres;
use Symfony\Component\HttpFoundation\Request;
use biyn\lvpBundle\Form\Type\MembresType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use biyn\lvpBundle\Service\Email;

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
        $form = $this->createForm(MembresType::class, $membre);
         
         # Traitement de la Requète
         $form->handleRequest($request);
         
         # Traitement POST
         if ($form->isSubmitted() && $form->isValid()) :

             # Hashage du Mot de Passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($membre);
            $membre->setMdp($encoder->encodePassword($membre->getPlaintextpassword(),null));
         
             # Enregistrement en BDD
             $em = $this->getDoctrine()->getManager();
             $em->persist($membre);
             $em->flush();
             
             # Envoi d'un Email
             $Email = $this->get(Email::class);
             $Email->sendInscriptionMessage($membre);

             # Envoi d'une Notification
             $session = $request->getSession();
             $session->getFlashBag()->add('notam', 'Merci, votre membre a bien été ajouté.');
             
             
             # Redirection vers la Page de Gestion des Membres      
             return $this->redirectToRoute('biynlvp_admin_membres');
             
         endif;
        
        # Passage à la Vue
        return $this->render('biynlvpBundle:Membre:inscription.html.twig', 
                        ['form' => $form->createView()]);
        
    }
    
}
