<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError; // Importation de la classe permettant de créer des erreurs dans les formulaires
use App\Recaptcha\RecaptchaValidator; // Importation de notre service de validation du captcha
use App\Form\VehicleType;
use App\Form\ContactType;
use App\Form\UserType;
use App\Entity\Vehicle;
use App\Entity\User;
use \Swift_Message; //Importation des deux classes necessaires pour envoyer un email
use \Swift_Mailer;

// T: téléversements
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
// T: throw errors (phase prod)
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

// JPG = Jean-Philippe
// T = Thierry
// B = brian

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
    public function contact(Request $request, RecaptchaValidator $recaptcha, Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            // Si le captcha n'est pas valide, on crée une nouvelle erreur dans le formulaire (ce qui l'empêchera de créer l'article et affichera l'erreur)
            // $request->request->get('g-recaptcha-response')  -----> code envoyé par le captcha dont la méthode verify() a besoin
            // $request->server->get('REMOTE_ADDR') -----> Adresse IP de l'utilisateur dont la méthode verify() a besoin
            if(!$recaptcha->verify( $request->request->get('g-recaptcha-response'), $request->server->get('REMOTE_ADDR') )){

                // Ajout d'une nouvelle erreur manuellement dans le formulaire
                $form->addError(new FormError('Le Captcha doit être validé !'));
            }
            if($form->isValid()){
                // Le mailer est récupéré automatiquement en paramètre par autowiring dans $mailer

                // Création du mail
                // T: indentation bon sang ! pas étonnant que ça fonctionne pas ensuite !
                $message = (new Swift_Message('Contact email'))
                    ->setFrom('locautovintage@noreply.com')     // Expediteur
                    ->setTo('destinataire@example.com')     // destinataire
                    ->setBody(
                        $this->renderView(
                                'email/email_inscription.html.twig'     // Version HTML du mail (une vue twig)
                            ),
                        'text/html'
                    )
                    ->addPart(
                        $this->renderView(
                                'email/email_inscription.txt.twig'      // Version textuelle du mail (un TXT/twig !)
                            ),
                        'text/plain'
                    )
                ;

                // Envoi du mail
                $mailer->send($message);
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
  * Si la personne qui essaye de venir sur cette page n'est pas connectée, elle sera redirigée à la page de connexion par le firewall
  * n'oublis pas le Request en argument !
  */
  public function profil(Request $request)
  {

    // search repository véhicles
    $vehicleRepository = $this->getDoctrine()->getRepository(Vehicle::class);
    $vehicles = $vehicleRepository->findAllByThisUser( $this->getUser() );

    // create form avec son handleRequest s'il te plait ! On évite de perdre une heure à chercher à cause de cecis.
    $formProfil = $this->createForm(UserType::class, $this->getUser());
    $formProfil->handleRequest($request);

    if($formProfil->isSubmitted() && $formProfil->isValid()){
        // send to datase && message de vainqueur && return to 'profil'
        $em = $this->getDoctrine()->getmanager();
        $em->persist($this->getUser());
        $em->flush();
        $this->addFlash('success', 'Votre profil fut modifié.');
        return $this->redirectToRoute('profil');
    }

    return $this->render('main/profil.html.twig',[
        'vehicles' => $vehicles,
        'formProfil' => $formProfil->createView(),
    ]);

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
* @Security("is_granted('ROLE_USER')")
*/
public function createVehicle(Request $request){

        $newVehicle = new Vehicle();
        $formVehicle = $this->createForm(VehicleType::class, $newVehicle);
        $formVehicle->handleRequest($request);

        if($formVehicle->isSubmitted() && $formVehicle->isValid()){

            // ici pour ajouter un owner et gérer les images. on commence par hydrater l'owner
            $newVehicle->setOwner( $this->getUser() );
            // inutile. symfony fait le café tout seul. on peut éventuellement removeVehicle() par contre !
            // dump($this->getUser()->addVehicle( $newVehicle ));

            /**
             * @var UploadedFile $pictureFile
             * T : big part setPictures https://symfony.com/doc/current/controller/upload_file.html
             * pas vraiment nécessaire cette ligne du haut je pense...
             */
            $pictureFile = $formVehicle->get('pictures')->getData();

            // this condition is needed because the 'pictures' field is not required
            // the picture is processed only when a file is uploaded
            if ($pictureFile) {

                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);

                // JPG : 1 = ID propriétaire / bmw = brand / 2002 = model / 1 = n° de l'image de 1 à 5
                // T : bon ça va finalement
                $newFilename =
                    $newVehicle->getOwner()->getId() .
                    $newVehicle->getBrand() .
                    $newVehicle->getModel() .
                    '-' .
                    1 // first one en dur
                ;

                // supprime les caractères spéciaux dans le nom de fichier, et en base de donnée
                $safeFilename = preg_replace("/[^A-Za-z0-9_-]/", '', $newFilename);

                // dépace ce fichier fraichement renomé au bon endroit
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $safeFilename
                    );
                } catch (FileException $e) {
                }

                // pour futur éventuelle optimisation
                // $arrayFilename[] = $newFilename;
                // dump($arrayFilename);

                // sauvegarde ce nom de fichier dans le !TABLEAU! pour futur transfert en base de donnée
                $newVehicle->setPictures([$safeFilename]);

            }

            // envoie tout ça en database
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

// do not tuch at dat stache '{'
}
