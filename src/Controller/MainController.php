<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\User;
use App\Entity\Vehicle;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/autos-disponibles/", name="car_list")
     * en cas de modification du name, penser à /locautovintage/templates/base.html.twig
     * JPG - Affiche la 1ere photo de chaque voiture disponible dans la collection
     */
    public function carList()
    {
        $vehicleRepository = $this->getDoctrine()->getRepository(Vehicle::class);

        $vehicles = $vehicleRepository->findAll();
        dump($vehicles);
        return $this->render('main/carList.html.twig', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * @Route("/auto-details/{id}", name="car_detail")
     * en cas de modification du name, penser à /locautovintage/templates/base.html.twig
     * JPG - Affiche la fiche technique de la voiture sélectionnée sur la page 'autos-disponibles'
     */
    public function carDetail(Vehicle $vehicle)
    {
        return $this->render('main/carDetail.html.twig', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * @Route("/test-json/", name="test_json")
     */
    public function testJson()
    {
        $vehicleRepository = $this->getDoctrine()->getRepository(Vehicle::class);
        $vehicles = $vehicleRepository->findAll();

        /**
         * on reconstruit un beau tableau pour éviter la circular référence
         * l'object qui contient des objects à l'infini
         * le serpent qui se mort la queue.
         */
        foreach($vehicles as $vehicle){
            $returnVehicles[] = [
                'city' => $vehicle->getOwner()->getCity(),
                'firstname' => $vehicle->getOwner()->getFirstname(),
                'year_produce' => $vehicle->getYearProduced(),
                'picture' => $vehicle->getPictures(),
            ];
        }

        return $this->json([
            'returnVehicles' => $returnVehicles,
        ]);

    }

    /**
     * @Route("/contactez-nous", name="contact")
     */
    public function contact()
    {
        return $this->render('main/contact.html.twig');
    }

/**
  * @Route("/profil", name="profil")
  * @Security("is_granted('ROLE_USER')")
  */
  public function profil()
  {
      // Si la personne qui essaye de venir sur cette page n'est pas connectée, elle sera redirigée à la page de connexion par le firewall
 
      return $this->render('main/profil.html.twig');
  }
 
 
 /**
  * @Route("/administration", name="admin")
  * @Security("is_granted('ROLE_ADMIN')")
  */
 public function admin()
 {
      // Si la personne qui essaye de venir sur cette page n'a pas le rôle ROLE_ADMIN, elle sera redirigée à la page de connexion si elle n'est pas connecté ou bien sur une page 403 si elle l'est mais n'est pas admin.
 
      return $this->render('main/admin.html.twig');
 }
// do not tuch at dat '{'
}
