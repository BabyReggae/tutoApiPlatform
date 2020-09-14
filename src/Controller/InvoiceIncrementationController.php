<?php 

namespace App\Controller;

use App\Entity\Invoice;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceIncrementationController{

    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager )
    {
        $this->manager = $manager;
    }

    public function __invoke(Invoice $data )
    {   
        $data->setChrono(  $data->getChrono() + 10 );
         $this->manager->persist( $data );
        
          $this->manager->flush();

          return $data;
    }

}

?>