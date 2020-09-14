<?php

namespace App\Events;

use App\Kernel;
use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PwdEncoderSubscriber implements EventSubscriberInterface {

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword' , EventPriorities::PRE_WRITE ]
        ];
    }

    public function encodePassword(ViewEvent  $event ){
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if( !$result instanceof User || $method != "POST" ) return;
        $user = $result;
        $hash = $this->encoder->encodePassword( $user, $user->getPassword() );
        $user->setPassword( $hash );
    }
}

?>