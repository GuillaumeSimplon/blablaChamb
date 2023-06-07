<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;
use App\Entity\Rule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function annonces(EntityManagerInterface $entityManagerInterface): Response
    {

        $repository = $entityManagerInterface->getRepository(Ride::class);
        $rides = $repository->findAll();

        $repository = $entityManagerInterface->getRepository(Car::class);
        $car = $repository->findAll();

        return $this->render('home/annonces.html.twig', [
            "controller_name" => 'HomeController',
            "logo" => 'blablaChamb',
            "rides" => $rides,
            "car" => $car,
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
