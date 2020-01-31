<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// besoin de Vehicle et Faker
use App\Entity\Vehicle;
// use Faker;
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
        // encore une fois je balance ça en dur (voir UserFixtures)
        /*
        $faker = Faker\Factory::create('fr_FR');

        // entre les 4 utilisateurs, penser à changer en cas de soucis mais aussi sur UserFixtures.php
        for($i = 0; $i <= 5; $i++){
            $newVehicle = new Vehicle();
            $newVehicle
                ->setBrand($faker->company)
                ->setModel($faker->word)
                // $faker->year
                ->setYearProduced('1978')
                ->setEngineType($faker->randomDigit())
                ->setEngineDisplacement($faker->randomNumber(2))
                ->setEnginePower($faker->randomNumber(4))
                ->setMaxSpeed($faker->randomNumber(3))
                ->setMaxSeats($faker->randomDigit())
                // entre les 4 utilisateurs, penser à changer en cas de soucis  mais aussi sur UserFixtures.php
                ->setOwner( $this->getReference('user' . $faker->biasedNumberBetween(0,5,'sqrt')) )
                ->setPictures(['1bmw2002-3.jpg','1lotuselises1-2.jpg','3mgb-4.jpg','3peugeot504cabriolet-4.jpg','3porsche964-3.jpg'])
            ;
            */

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
        for($i = 0; $i <= 5; $i++){
            $newVehicle = new Vehicle();
            if($i == 0){
                $newVehicle
                    ->setBrand('BMW')
                    ->setModel('2002')
                    ->setYearProduced('1972')
                    ->setEngineType('4 cylindres en ligne')
                    ->setEngineDisplacement(1990)
                    ->setEnginePower(100)
                    ->setMaxSpeed(173)
                    ->setMaxSeats(4)
                    ->setOwner($this->getReference('user' . 0))
                    ->setPictures(['1bmw2002-1.jpg','1bmw2002-2.jpg','1bmw2002-3.jpg','1bmw2002-4.jpg','1bmw2002-5.jpg'])
                ;
            } else if($i == 1){
                $newVehicle
                    ->setBrand('Jaguar')
                    ->setModel('xk120')
                    ->setYearProduced('1958')
                    ->setEngineType('6 cylindres en ligne')
                    ->setEngineDisplacement(3442)
                    ->setEnginePower(250)
                    ->setMaxSpeed(212)
                    ->setMaxSeats(2)
                    ->setOwner($this->getReference('user' . 1))
                    ->setPictures(['2jaguarxk120-1.jpg','2jaguarxk120-2.jpg','2jaguarxk120-3.jpg','2jaguarxk120-4.jpg','2jaguarxk120-5.jpg'])
                ;
            } else if($i == 2){
                $newVehicle
                    ->setBrand('Lotus')
                    ->setModel('Elise-S1')
                    ->setYearProduced('1998')
                    ->setEngineType('4 cylindres en ligne')
                    ->setEngineDisplacement(1796)
                    ->setEnginePower(118)
                    ->setMaxSpeed(201)
                    ->setMaxSeats(2)
                    ->setOwner($this->getReference('user' . 0))
                    ->setPictures(['1lotuselises1-1.jpg','1lotuselises1-2.jpg','1lotuselises1-3.jpg','1lotuselises1-4.jpg','1lotuselises1-5.jpg'])
                ;
            } else if($i == 3){
                $newVehicle
                    ->setBrand('Mg')
                    ->setModel('B')
                    ->setYearProduced('1974')
                    ->setEngineType('4 cylindres en ligne')
                    ->setEngineDisplacement(1798)
                    ->setEnginePower(95)
                    ->setMaxSpeed(170)
                    ->setMaxSeats(2)
                    ->setOwner($this->getReference('user' . 2))
                    ->setPictures(['3mgb-1.jpg','3mgb-2.jpg','3mgb-3.jpg','3mgb-4.jpg','3mgb-5.jpg'])
                ;
            } else if($i == 4){
                $newVehicle
                    ->setBrand('Peugeot')
                    ->setModel('504 Cabriolet')
                    ->setYearProduced('1969')
                    ->setEngineType('4 cylindres en ligne')
                    ->setEngineDisplacement(1800)
                    ->setEnginePower(97)
                    ->setMaxSpeed(179)
                    ->setMaxSeats(4)
                    ->setOwner($this->getReference('user' . 2))
                    ->setPictures(['3peugeot504cabriolet-1.jpg','3peugeot504cabriolet-2.jpg','3peugeot504cabriolet-3.jpg','3peugeot504cabriolet-4.jpg','3peugeot504cabriolet-5.jpg'])
                ;
            } else if($i == 5){
                $newVehicle
                    ->setBrand('Porsche')
                    ->setModel('964')
                    ->setYearProduced('1991')
                    ->setEngineType('6 cylindres à plat')
                    ->setEngineDisplacement(3600)
                    ->setEnginePower(250)
                    ->setMaxSpeed(260)
                    ->setMaxSeats(4)
                    ->setOwner($this->getReference('user' . 2))
                    ->setPictures(['3porsche964-1.jpg','3porsche964-2.jpg','3porsche964-3.jpg','3porsche964-4.jpg','3porsche964-5.jpg'])
                ;
            }

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
