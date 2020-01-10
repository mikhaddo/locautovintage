<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
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


// do not tuch at dat '{'
}
