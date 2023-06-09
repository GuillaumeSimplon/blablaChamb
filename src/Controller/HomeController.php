<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;
use App\Entity\Rule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/')]

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntityManagerInterface $entityManagerInterface): Response
    {

        return $this->render('home/index.html.twig', [
            "controller_name" => 'HomeController',
        ]);
    }



    #[Route('/covoiturage', name: 'app_covoiturage')]
    public function covoiturage(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('home/covoiturage.html.twig', [
            "controller_name" => 'HomeController',
        ]);
    }


    #[Route('/reservation', name: 'app_reservation')]
    public function reservation(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('home/reservation.html.twig', [
            "controller_name" => 'HomeController',
        ]);
    }








    #[Route('/annonces', name: 'app_annonces')]
    public function annonces(EntityManagerInterface $entityManagerInterface, Request $request, Security $security): Response
    {
        $user = $security->getUser();
        
        $repository = $entityManagerInterface->getRepository(Ride::class);
        $rides = $repository->findAll();

        if ($request->isMethod('POST')) {
            $rideId = $request->request->get('ride_id');
            $ride = $repository->find($rideId);

            // Vérifiez si l'utilisateur actuel est autorisé à supprimer cette annonce
            // if ($user !== $ride->getDriver()) {
            //     throw $this->createAccessDeniedException('You are not allowed to delete this ride.');
            // }
    
            // Supprimez l'annonce de la base de données
            $entityManagerInterface->remove($ride);
            $entityManagerInterface->flush();
    
            // Redirigez vers une page appropriée après la suppression
            return $this->redirectToRoute('app_annonces');
        }

        $repository = $entityManagerInterface->getRepository(Car::class);
        $car = $repository->findAll();

        return $this->render('home/annonces.html.twig', [
            "controller_name" => 'HomeController',
            "logo" => 'blablaChamb',
            "rides" => $rides,
            "car" => $car,
            "user" => $user,
        ]);
    }




    #[Route('/detailAnnonce/{id}', name: 'app_detailAnnonce')]
    public function detailAnnonce($id, EntityManagerInterface $entityManagerInterface): Response
    {

        $annonceRepository = $entityManagerInterface->getRepository(Ride::class);
        $annonce = $annonceRepository->find($id);

        // $carRepository = $entityManagerInterface->getRepository(Car::class);
        $car = $annonceRepository->find($id)->getCar();

        return $this->render('home/detailAnnonce.html.twig', [
            'ride' => $annonce,
            "car" => $car,
        ]);
    }
}
