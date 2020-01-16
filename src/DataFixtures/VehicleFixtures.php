<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// besoin de Vehicle et Faker
use App\Entity\Vehicle;
use Faker;
// timmy ?
// use \DateTime;

// lire les fichiers dans l'ordre (VehicleFixtures needs UserFixtures first !)
// https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * implémente DependentFixtureInterface pour charger le fichier de dépendance avant celui ci.
 * tout est écrit en dur, penser à utiliser mieux que cela Faker.
 * https://packagist.org/packages/fzaninotto/faker
 */
class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // entre les 4 utilisateurs, penser à changer en cas de soucis mais aussi sur UserFixtures.php
        for($i = 0; $i <= 3; $i++){
            $newVehicle = new Vehicle();
            $newVehicle
                ->setBrand($faker->company)
                ->setModel('mik')
                // $faker->year
                ->setYearProduced(1978)
                ->setEngineType(50)
                ->setEngineDisplacement(4)
                ->setEnginePower(20000)
                ->setMaxSpeed(200)
                ->setMaxSeats(2)
                // entre les 4 utilisateurs, penser à changer en cas de soucis  mais aussi sur UserFixtures.php
                ->setOwner( $this->getReference('user' . $faker->biasedNumberBetween(0,3,'sqrt')) )
                ->setPictures(['1bmw2002-3.jpg','1lotuselises1-2.jpg'])
            ;

            /**
             * Utilise pas ça c'est pour UserFixtures
             * ou alors adaptes-y en cas de besoin
             */
            // if($i == 0){
            //     $newVehicle->setPictures(['1bmw2002-3.jpg']);
            //     $newVehicle->setMaxSpeed(500);
            // } else if($i != 0){
            //     $newVehicle->setPictures(['1bmw2002-3.jpg','1lotuselises1-2.jpg']);
            // }

            $manager->persist($newVehicle);
        }

        $manager->flush();
    }

    /**
     * fichier de dépendance à charger avant celui ci
     */
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
