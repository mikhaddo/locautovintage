<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Route("/autos-disponibles", name="car_map")
     * en cas de modification du name, penser à /locautovintage/templates/base.html.twig
     */
    public function carMap()
    {
        return $this->render('main/carMap.html.twig');
    }

    /**
     * @Route("/test-json/", name="test_json")
     */
    public function testJson()
    {

        $fruits = ['Fraise', 'Orange', 'Banane', 'Pomme', 'Poire'];

        return $this->json([
            'fruits' => $fruits
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
