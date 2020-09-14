<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {



        $faker = Factory::create('fr_FR');

        $chrono = 1;
        

        for ($x=0; $x < mt_rand( 5, 10 ); $x++) { 
            $user  = new User();

            $hash = $this->encoder->encodePassword( $user , "bonsoir" );
            $chrono = 1;

            $user
            ->setFirstName( $faker->firstName() )
            ->setLastName( $faker->lastName )
            ->setEmail( $faker->email )
            ->setPassword( $hash );

            $manager->persist( $user );

            for ($c=0; $c < mt_rand( 6  , 20 ); $c++) { 
                $customer  = new Customer();
                $customer
                ->setFirstName( $faker->firstName )
                ->setLastName( $faker->lastName )
                ->setEmail( $faker->email ) 
                ->setCompany( $faker->company )
                ->setUser( $user )
                
                ;
    
                $manager->persist( $customer );

                    for ($i=0; $i < mt_rand( 2 , 5 ); $i++) { 
                        $fact = new Invoice();
        
                        $fact
                        ->setSentAt( $faker->dateTimeBetween( '-6 months' ) )
                        ->setAmount( $faker->randomFloat(  2 , 23 , 5879 ) )
                        ->setStatus( $faker->randomElement( ['ETAT1' , 'ETAT2' , 'ETAT3'] ))
                        ->setChrono( $chrono )
                        ->setCustomer( $customer );
                        
                        echo( $faker->randomFloat(  2 , 23 , 5879 ) );
                        echo "\n\r";

                        $chrono++;
        
                        $manager->persist( $fact );
                    }
                }


        }



        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
