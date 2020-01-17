<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// inclut User et Faker
use App\Entity\User;
use Faker;

// les mots de passe
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // entre les 4 utilisateurs, penser à changer en cas de soucis mais aussi sur VehicleFixtures.php
        for($i = 0; $i <= 3; $i++){
            $newUser = new User();
            $newUser
                ->setEmail($faker->email)
                // en principe pas besoin de définir pour tout le monde ROLE_USER, à vérifier
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $newUser,
                    'celestin'
                ))
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                // en dur
                ->setPostcode(71400)
                ->setPhoneNumber($faker->phoneNumber)
                // en dur aussi
                ->setInsuranceName('Titus Corp.')
            ;

            /**
             * pour donner aussi le role admin et la ville autun au premier utilisateur
             * les autres auront une citée fake
             */
            if($i == 0){
                $newUser->setRoles(['ROLE_ADMIN']);
                $newUser->setCity('autun');
            } else if($i != 0){
                $newUser->setCity($faker->city);
            }

            //->addVehicle() be carrefull with that -> got to VehicleFixtures.php
            $this->addReference('user' . $i, $newUser);

            $manager->persist($newUser);
        }

        $manager->flush();
    }

    // vielle technique obsolete pour classer les fichiers
    // use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
    // public function getOrder()
    // {
    //     return 1;
    // }
}
