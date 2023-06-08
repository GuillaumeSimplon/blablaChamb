<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;
use App\Entity\Rule;
use App\Form\CreateAnnonceType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



#[Route('/')]

class CreateAnnonceController extends AbstractController
{
    #[Route('/createAnnonce', name: 'app_createAnnonce')]
    public function annonces(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $ride = new Ride();


        $form = $this->createForm(CreateAnnonceType::class, $ride);
        $form->handleRequest($request);

        $repository = $entityManagerInterface->getRepository(Ride::class);
        $rides = $repository->findAll();


        $repository = $entityManagerInterface->getRepository(Rule::class);
        $rule = $repository->findAll();

        $repository = $entityManagerInterface->getRepository(Car::class);
        $car = $repository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            // Persiste les données du formulaire dans l'entité Demo
            $ride = $form->getData();
            $ride->setCreated(new DateTime);
            $ride->setDriver($this->getUser());
            $entityManagerInterface->persist($ride);
            $entityManagerInterface->flush();
            // Exécuter la logique que vous souhaitez
            // par exemple enregistrer la nouvelle entité en base de données
        }


        return $this->render('home/createAnnonce.html.twig', [
            "controller_name" => 'HomeController',
            "logo" => 'blablaChamb',
            "rides" => $rides,
            "rule" => $rule,
            "car" => $car,
            "form" => $form->createView(),
        ]);
    }
}
