<?php

namespace biyn\lvpBundle\Controller;

use biyn\lvpBundle\Entity\Encode;
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
//        $cours = $coursRepository->findAll();
        $cours = $coursRepository->findBy([], ['id' => 'DESC']);

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

    public function mdpoublieAction(Request $request) {

        # Initialisation des erreurs
        $error = '';
        $success = '';

        if($request->isMethod('POST')) :

            # Vérification si l'adresse email existe...
            $membresRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('biynlvpBundle:Membres');

            $membre = $membresRepository->findOneByEmail($request->get('email'));

            if (!$membre) {
                # Aucun membre avec cette adresse email
                $error = "Nous n'avons pas trouvé votre adresse email.<br>Contactez directement notre secrétaire au <strong>06 60 29 50 56</strong> <br>ou par email : <strong>lavoixprophetique@gmail.com</strong>";
            } else {
                # Réinitialisation du Mot de Passe.
                # Envoi d'un Email
                $Email = $this->get(Email::class);
                $Email->sendMotDePasseOublieMessage($membre);
                # Affichage d'une Confirmation
                $success = "<h2>Merci !</h2>Vous pouvez maintenant consulter vos emails.<br>Nous vous avons fait parvenir un lien de réinitialisation.<br><strong>Pensez à vérifier vos spams.</strong>";
            }

        endif;

        return $this->render('biynlvpBundle:Membre:mdpoublie.html.twig',[
            'error' => $error,
            'success' => $success
        ]);
    }

    public function mdpresetAction($token, Request $request) {

        if($request->isMethod('POST')) :

            # Récupération du Token
            $e = new Encode;
            $idmembre = $e->decode($token);

            # Récupération du Membre
            $em = $this->getDoctrine()->getManager();
            $membre = $em->getRepository(Membres::class)->findOneById($idmembre);

            if (!$membre) {
                throw $this->createNotFoundException(
                    'Aucun utilisateur ne correspond à ce token : '.$token
                );
            }

            # Hashage du Mot de Passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($membre);
            $membre->setMdp($encoder->encodePassword($request->get('password'),null));

            # Persiste en BDD
            $em->flush();

            return $this->redirect('/membre/connexion?msg=Félicitation, votre mot de passe à bien été mis à jour, vous pouvez-vous connecter.');

        endif;

        return $this->render('biynlvpBundle:Membre:mdpreset.html.twig');
    }
    
}





















