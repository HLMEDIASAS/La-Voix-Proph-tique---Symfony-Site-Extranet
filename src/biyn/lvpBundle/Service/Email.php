<?php
namespace biyn\lvpBundle\Service;

use Symfony\Component\Templating\EngineInterface;
use biyn\lvpBundle\Entity\Membres;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use biyn\lvpBundle\Entity\Cours;

class Email
{
    # Déclaration des propriétés
    protected $mailer,
              $template,
              $router,
              $requestStack;
    
    public function __construct(\Swift_Mailer $mailer, EngineInterface $template, UrlGeneratorInterface $router, RequestStack $requestStack) {
        $this->mailer        = $mailer;
        $this->template      = $template;
        $this->router        = $router;
        $this->requestStack  = $requestStack;
    }
    
    public function sendInscriptionMessage(Membres $membre)
    {
        # Définition des Variables
        $template = 'biynlvpBundle:Mail:inscription.html.twig';
        
        $from = 'lavoixprophetique@gmail.com';
        $to = $membre->getEmail();
        $subject = '[La Voix Prophétique] - Vos identifiants de connexion';
        
        $request = $this->requestStack->getCurrentRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        
        $message = "Bonjour ".$membre->getPrenom().", Bienvenue &agrave; la Voix Proph&eacute;tique ! <br /><u>Voici les acc&egrave;s &agrave; votre Espace Membre :</u><br /><br /><b>Utilisateur :</b> ".$membre->getEmail()."<br /><b>Mot de Passe :</b> ".$membre->getPlaintextpassword()."<br /><b>Adresse :</b> <a href='".$this->router->generate('biynlvp_espacemembres', [], UrlGeneratorInterface::ABSOLUTE_URL)."'>".$this->router->generate('biynlvp_espacemembres', [], UrlGeneratorInterface::ABSOLUTE_URL)."</a><br /><br /><a href='".$this->router->generate('biynlvp_espacemembres', [], UrlGeneratorInterface::ABSOLUTE_URL)."' style='display: inline-block; padding: 11px 30px; margin: 10px 0px 20px; font-size: 15px; color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;'> D&eacute;couvrir mon Espace Membre </a><br /><br />A tr&egrave;s vite,<br />La Secrétaire, Esther.";
        $body = $this->template->render($template, ['membre' => $membre, 'message' => $message, 'biynlvp_homepage' => $this->router->generate('biynlvp_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL), 'baseurl' => $baseurl]);
       
        # Envoi du Message
        $this->process($from, $to, $subject, $body);
    }

    public function sendNouveauCourMessage($membres, Cours $cour)
    {
        # Définition des Variables
        $template = 'biynlvpBundle:Mail:inscription.html.twig';
        
        $from = 'lavoixprophetique@gmail.com';
        //$to = $membre->getEmail();
        $subject = '[La Voix Prophétique] - Nouveau cour disponible';
        
        $request = $this->requestStack->getCurrentRequest();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        
        # Envoi d'un Email à chaque membre
        foreach ($membres as $membre) :
            $message = "Bonjour ".$membre->getPrenom().",<br /><br />Le cour &ldquo;".$cour->getTitre()."&rdquo; est disponible sur votre Espace Membre.<br /><br /><a href='".$this->router->generate('biynlvp_espacemembres', [], UrlGeneratorInterface::ABSOLUTE_URL)."' style='display: inline-block; padding: 11px 30px; margin: 10px 0px 20px; font-size: 15px; color: #fff; background: #00c0c8; border-radius: 60px; text-decoration:none;'> Connexion &agrave; mon Espace Membre </a><br /><br />A tr&egrave;s vite,<br />La Secrétaire, Esther.";
            $body = $this->template->render($template, ['membre' => $membre, 'message' => $message, 'biynlvp_homepage' => $this->router->generate('biynlvp_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL), 'baseurl' => $baseurl]);
       
            # Envoi du Message
            $this->process($from, $membre->getEmail(), $subject, $body);
        endforeach;
    }
    
    protected function process($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
    
        $mail
        ->setFrom($from)
        ->setTo($to)
        ->setSubject($subject)
        ->setBody($body)
        ->setContentType('text/html');
        
        $this->mailer->send($mail);
    }
}

