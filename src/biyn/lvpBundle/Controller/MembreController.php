<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
