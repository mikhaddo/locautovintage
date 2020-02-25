<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\VehicleDetailsType;
use App\Entity\Vehicle;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VehicleDetailsController extends AbstractController
{
    /**
     * @Route("/auto-details/{id}", name="car_detail")
     * JPG - Affiche la fiche technique de la voiture sélectionnée sur la page 'autos-disponibles'
     * T - on va y aller tranquillement pour faire quelque chose de très fonctionnel techniquement !
     */
    public function carDetail(Vehicle $vehicle, Request $request)
    {

        /**
         * drapeau bool de vérification, accès au formulaire annulé sur la vue twig
         * ensuite on crée le formulaire avec son handleRequest et la Request en argument.
         * il s'agit de bien vérifier si l'utilisateur est propriétaire du véhicule,
         * pour lui donner les droits de modification, et un accès au formulaire en vue twig.
         */
        $verificationUserOwnage = FALSE;
        $formDetailsVehicle = $this->createForm(VehicleDetailsType::class, $vehicle);
        $formDetailsVehicle->handleRequest($request);

        // dump($vehicle);

        // si un utilisateur est connecté
        if( $this->getUser() !== null ){

            // T :: mise en place de mes petites vérifications de débuggage
            /*
            dump(
                $this->getUser()->getId() .
                ' == ' .
                $vehicle->getOwner()->getId() .
                ' ? ' .
                ( $this->getUser()->getId() == $vehicle->getOwner()->getId() )
            );
            */

            // si l'ID de l'utilisateur en cours est == à l'ID_owner du véhicule inspecté
            // c'est donc le propriétaire du véhicule il semble
            if( $this->getUser()->getId() == $vehicle->getOwner()->getId() ){

                // le drapeau de vérification est levé ! accès au formulaire sur la vue twig.
                $verificationUserOwnage = true;

                // here we can continue form verifications, hydratations !
                if($formDetailsVehicle->isSubmitted() && $formDetailsVehicle->isValid()){

                    /**
                     * @var UploadedFile $pictureFile
                     * T : big part setPictures https://symfony.com/doc/current/controller/upload_file.html
                     * pas vraiment nécessaire cette ligne du haut je pense...
                     */
                    $pictureFile = $formDetailsVehicle->get('pictures')->getData();

                    // this condition is needed because the 'pictures' field is not required
                    // the picture is processed only when a file is uploaded
                    if ($pictureFile) {

                        $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                        // besoin d'une condition qui vérifie le nombre d'images et qui incrémente jusqu'à cinq !
                        // pour chaques vielles photos existantes on les décompacte dans un tableau
                        // vérifier que ça ne plante pas si aucun vehicule en base de donnée !
                        foreach( $vehicle->getPictures() as $vehicleOldTabPicture ) {
                            // dump($vehicleOldTabPicture);
                            $arrayFilename[] = $vehicleOldTabPicture;
                        }

                        // si il n'y a pas déjà cinq photographies
                        if(count($arrayFilename) < 5){

                            // JPG : 1 = ID propriétaire / bmw = brand / 2002 = model / 1 = n° de l'image de 1 à 5
                            // T : okaye !
                            $newFilename =
                                $vehicle->getOwner()->getId() .
                                $vehicle->getBrand() .
                                $vehicle->getModel() .
                                '-' .
                                ( count($arrayFilename) + 1 ) .
                                '.' .
                                $pictureFile->getClientOriginalExtension()
                            ;

                            // supprime les caractères spéciaux dans le nom de fichier, et en base de donnée
                            // rajout du '.' pour le '.jpg' par exemple.
                            // en fait cette regex enlève tout ce qui ne fait pas partie avec négation '^' méfiance.
                            $safeFilename = preg_replace("/[^A-Za-z0-9\_\-\.]/", '', $newFilename);

                            // dépace ce fichier fraichement renomé au bon endroit
                            try {
                                $pictureFile->move(
                                    $this->getParameter('pictures_directory'),
                                    $safeFilename
                                );
                            } catch (FileException $e) {
                            }

                            // on hydrate le tableau des véhicules avec notre nouvel upload
                            $arrayFilename[] = $safeFilename;
                            // dump($arrayFilename);

                            // sauvegarde ce tableau pour futur transfert en base de donnée
                            $vehicle->setPictures($arrayFilename);

                        } else {
                            $this->addFlash('error', 'déjà trop de photographies.');
                        }

                    }

                    // send to datase && message de vainqueur && return to 'profil' with /{id}
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($vehicle);
                    $em->flush();
                    $this->addFlash('success', 'Votre véhicule fut modifié.');
                    return $this->redirectToRoute('car_detail', [
                        'id' => $vehicle->getId(),
                    ]);
                }
            }

        }

        return $this->render('main/carDetail.html.twig', [
            'vehicle' => $vehicle,
            'verificationUserOwnage' => $verificationUserOwnage,
            'formDetailsVehicle' => $formDetailsVehicle->createView(),
        ]);
    }
}
