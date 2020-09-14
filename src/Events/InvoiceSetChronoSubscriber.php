<?php
namespace App\Events;

use App\Entity\Invoice;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\InvoiceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


//InvoiceSetChronoSubscriber
class InvoiceSetChronoSubscriber implements EventSubscriberInterface{
    
    private $security;
    private $repository;

    public function __construct(Security $security, InvoiceRepository $repository )
    {
        $this->security = $security;
        $this->repository = $repository;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setChronoForInvoice' , EventPriorities::PRE_VALIDATE ]
        ];
    }

    public function setChronoForInvoice(ViewEvent  $event){

        $res = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if( !$res instanceof Invoice || $method != "POST" ) return;
        $invoice = $res;

        $user = $this->security->getUser();
        $nextChrono = $this->repository->getNextInvoiceChronoOfUser( $user );
        $invoice->setChrono( $nextChrono );

        dd( $nextChrono , $invoice );
    }

}


?>