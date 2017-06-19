<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('biynlvpBundle:Default:index.html.twig');
    }

    public function menuAction() {

        # Création des liens du Menu
        $menu = [
            ['titre' => 'Accueil', 'url' => $this->generateUrl('biynlvp_homepage')],
            ['titre' => 'Présentation', 'url' => '#presentation'],
            ['titre' => 'Inscription', 'url' => '#inscription'],
            ['titre' => 'Galerie de Photos', 'url' => '#galerie'],
            ['titre' => 'Contact', 'url' => '#contact'],
            ['titre' => 'Espace Membres', 'url' => $this->generateUrl('biynlvp_espacemembres')],
        ];

        # Envoi à la Vue
        return $this->render('biynlvpBundle:Default:menu.html.twig', ['menu' => $menu]);

    }

     public function menuMembreAction() {

        # Création des liens du Menu
        $menu = [
            ['titre' => '< Vers le site', 'url' => $this->generateUrl('biynlvp_homepage')],
            ['titre' => 'Espace Membres', 'url' => $this->generateUrl('biynlvp_espacemembres')],
            ['titre' => 'Connexion', 'url' => $this->generateUrl('biynlvp_connexion')],
            ['titre' => 'Inscription', 'url' => $this->generateUrl('biynlvp_inscription')],
        ];

        # Envoi à la Vue
        return $this->render('biynlvpBundle:Default:menu.html.twig', ['menu' => $menu]);

    }

}
