<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\ContactType;
use App\Recaptcha\RecaptchaValidator; // Importation de notre service de validation du captcha
use Symfony\Component\Form\FormError; // Importation de la classe permettant de créer des erreurs dans les formulaires
use App\Entity\User;
use App\Entity\Vehicle;
use App\Form\VehicleType;

// throw errors (phase prod)
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $form = $this->createForm(ContactType::class);
        if($form->isSubmitted()){
            // Si le captcha n'est pas valide, on crée une nouvelle erreur dans le formulaire (ce qui l'empêchera de créer l'article et affichera l'erreur)
            // $request->request->get('g-recaptcha-response')  -----> code envoyé par le captcha dont la méthode verify() a besoin
            // $request->server->get('REMOTE_ADDR') -----> Adresse IP de l'utilisateur dont la méthode verify() a besoin
        if(!$recaptcha->verify( $request->request->get('g-recaptcha-response'), $request->server->get('REMOTE_ADDR') )){

    // Ajout d'une nouvelle erreur manuellement dans le formulaire
    $form->addError(new FormError('Le Captcha doit être validé !'));
}
        if($form->isValid()){
            // Création d'un flash message de type "success"
            $this->addFlash('success', 'Votre message a bien été envoyé!');
            // Redirection de l'utilisateur sur la route "home" (la page d'accueil)
            return $this->redirectToRoute('home');
        }
    }
        return $this->render('main/contact.html.twig', [
            'form' =>$form->createView()
        ]);
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
/**
* @Route("/conditions-generales-dutilisation", name="conditions_utilisation")
*/
public function conditions()
    {
        return $this->render('main/conditions.html.twig');
    }
/**
* @Route("/formulaire-vehicule", name="form_vehicle")
*
*/
public function createVehicle(Request $request){

        $newVehicle = new Vehicle();
        $formVehicle = $this->createForm(VehicleType::class, $newVehicle);
        $formVehicle->handleRequest($request);

        if($formVehicle->isSubmitted() && $formVehicle->isValid()){

            $em = $this->getDoctrine()->getmanager();
            $em->persist($newVehicle);
            $em->flush();
            //Création d'un message flash de succès
            $this->addFlash('success', 'Votre véhicule a bien été ajouté.');

            return $this->redirectToRoute('profil');
        }
        return $this->render('main/vehicle.html.twig', [

            'formVehicle' => $formVehicle->createView()
        ]);
    }
// do not tuch at dat '{'
}
