<?php

namespace App\Controller;

use App\Entity\Ride;
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


    
    #[Route('./covoiturage', name: 'app_covoiturage')]
    public function covoiturage(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('home/covoiturage.html.twig');
    }




    #[Route('/annonces', name: 'app_annonces')]
    public function annonces(EntityManagerInterface $entityManagerInterface): Response
    {

        $repository = $entityManagerInterface->getRepository(Ride::class);

        $rides = $repository->findAll();

        return $this->render('home/annonces.html.twig', [
            "controller_name" => 'HomeController',
            "logo" => 'blablaChamb',
            "rides" => $rides
        ]);
    }
}
