<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// inclut User et Faker
use App\Entity\User;
// use Faker;

// les mots de passe
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;
    private $city;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * c'était un essai de function, mais je connais pas encore assez le dev orienté object.
     */
    /*
    public function generatorCities($number)
    {

       $SelectTableauCities = [
            'autun',
            'epinac',
            'auxy',
            'morlet',
            'curgy',
            'monthelon',
        ];
        echo $SelectTableauCities[$number];

        $this->city = $SelectTableauCities[$number];
    }
    */

    public function load(ObjectManager $manager)
    {
        /* maintenant on va faire cela en dur
        $faker = Faker\Factory::create('fr_FR');

        // entre les 4 utilisateurs, penser à changer en cas de soucis mais aussi sur VehicleFixtures.php
        for($i = 0; $i <= 5; $i++){
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
                // ->setCity( $this->generatorCities($i) )
            ;
            //echo $this->city->generatorCities($1);

            /**
             * pour donner aussi le role admin et la ville autun au premier utilisateur
             * les autres auront une citée fake
             */

        for($i = 0; $i <= 2; $i++){
            $newUser = new User();
            if($i == 0){
                $newUser
                    ->setEmail('martin@test.fr')
                    // en principe pas besoin de définir pour tout le monde ROLE_USER
                    ->setRoles(['ROLE_ADMIN'])
                    ->setPassword($this->passwordEncoder->encodePassword(
                        $newUser,
                        'celestin'
                    ))
                    ->setLastname('Martin')
                    ->setFirstname('Jean')
                    ->setPostcode(71200)
                    ->setPhoneNumber('0612131514')
                    ->setInsuranceName('AXA')
                    ->setCity('Creusot')
                ;
            } else if($i == 1){
                $newUser
                    ->setEmail('dupond@test.fr')
                    // en principe pas besoin de définir pour tout le monde ROLE_USER
                    ->setPassword($this->passwordEncoder->encodePassword(
                        $newUser,
                        'celestin'
                    ))
                    ->setLastname('Dupont')
                    ->setFirstname('Paul')
                    ->setPostcode(71400)
                    ->setPhoneNumber('0632659874')
                    ->setInsuranceName('MMA')
                    ->setCity('Autun')
                ;
            } else if($i == 2){
                $newUser
                    ->setEmail('monnot@test.fr')
                    // en principe pas besoin de définir pour tout le monde ROLE_USER
                    ->setPassword($this->passwordEncoder->encodePassword(
                        $newUser,
                        'celestin'
                    ))
                    ->setLastname('Monnot')
                    ->setFirstname('Pierre')
                    ->setPostcode(71100)
                    ->setPhoneNumber('0632145214')
                    ->setInsuranceName('GENERALI')
                    ->setCity('Chalon-sur-Saone')
                ;
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
