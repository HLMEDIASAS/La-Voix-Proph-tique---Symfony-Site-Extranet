<?php
/**
 * Created by PhpStorm.
 * User: Hugo LIEGEARD
 * Date: 14/01/2018
 * Time: 01:53
 */

namespace biyn\lvpBundle\EventListener;


use biyn\lvpBundle\Entity\Membres;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

# : https://thisdata.com/blog/subscribing-to-symfonys-security-events/
class MemberIsConnected implements EventSubscriberInterface
{

    private $manager;

    /**
     * MemberIsConnected constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        # Récupération du Token de connexion de l'utilisateur
        $token = $event->getAuthenticationToken();

        if (!$token instanceof UsernamePasswordToken) {
            throw new \RuntimeException(sprintf('Authentication token must be a %s instance.', UsernamePasswordToken::class));
        }

        # Récupération du Membre
        $membre = $token->getUser();


        if ($membre instanceof Membres) {
            # Mise à jour du Timestamp
            $membre->setDerniereconnexion();
            # Sauvegarde en BDD
            $this->manager->flush();
        }
    }

    # https://github.com/EnMarche/en-marche.fr/blob/master/src/Security/AdherentLoginTimestampRecorder.php
    # https://symfony.com/doc/current/components/security/authentication.html#authentication-events
    public static function getSubscribedEvents()
    {
        return [
          SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }
}
