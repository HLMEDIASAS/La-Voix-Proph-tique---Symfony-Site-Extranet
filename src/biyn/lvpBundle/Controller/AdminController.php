<?php

namespace biyn\lvpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use biyn\lvpBundle\Entity\Membres;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\BrowserKit\Request;

class AdminController extends Controller
{
    public function membresAction()
    {
        # Récupération du Repository
        $membresRepository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('biynlvpBundle:Membres');
        
        $membres = $membresRepository->findAll();        
        
        return $this->render('biynlvpBundle:Admin:membres.html.twig', ['membres' => $membres]);
    }
    
}










