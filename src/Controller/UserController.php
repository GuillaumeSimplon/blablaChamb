<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;
use App\Entity\Rule;
use App\Form\UserProfileType;
use App\Form\UserCarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/')]
class UserController extends AbstractController
{
    //////////////////////
    // PROFIL
    #[Route('/profil', name: 'app_profil')]
    public function profil(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();

        $repository = $entityManagerInterface->getRepository(User::class);
        $user = $repository->find($userId);

        $repository = $entityManagerInterface->getRepository(Ride::class);
        $ride = $repository->find($userId);

        $repository = $entityManagerInterface->getRepository(Car::class);
        $car = $repository->find($userId);

        $repository = $entityManagerInterface->getRepository(Rule::class);
        $rule = $repository->find($userId);

        $showForm = false; // Variable de contrôle pour afficher le formulaire

        // Créez un formulaire pour la modification du profil
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Effectuez les opérations de mise à jour de l'utilisateur ici
            $entityManagerInterface->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            // Redirigez l'utilisateur vers la page du profil après la mise à jour
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('home/profil.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'ride' => $ride,
            'car' => $car,
            'rule' => $rule,
            'form' => $form->createView(),
            'showForm' => $showForm,
        ]);
    }





    //////////////////////
    // EDIT PROFIL
    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Créez une instance de votre formulaire UserProfileType
        $form = $this->createForm(UserProfileType::class, $user);

        // Soumettez le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est valide, vous pouvez mettre à jour les informations de l'utilisateur
            $entityManager->flush();

            // Redirigez l'utilisateur vers la page du profil avec un message de succès
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_profil');
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, affichez le formulaire à nouveau
        return $this->render('home/edit_profil.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }













        //////////////////////
    // EDIT PROFIL CAR ???????????????????????????????????????????????????????????
// EDIT PROFIL CAR
// EDIT PROFIL CAR
#[Route('/profil/editCar', name: 'app_profil_editCar')]
public function editCar(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    $car = $user->getCar(); // Récupérez la voiture liée à l'utilisateur

    if (!$car) {
        $car = new Car(); // Créez une nouvelle instance de Car s'il n'y a pas de voiture liée
        $user->setCar($car); // Liez la voiture à l'utilisateur
    }

    // Créez une instance de votre nouveau formulaire UserCarType en utilisant la voiture
    $form = $this->createForm(UserCarType::class, $car);

    // Soumettez le formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Le formulaire est valide, vous pouvez mettre à jour les informations de la voiture
        $entityManager->persist($car); // Persistez la voiture dans l'EntityManager
        $entityManager->flush();

        // Redirigez l'utilisateur vers la page du profil avec un message de succès
        $this->addFlash('success', 'Profil mis à jour avec succès.');
        return $this->redirectToRoute('app_profil');
    }

    // Si le formulaire n'est pas soumis ou n'est pas valide, affichez le formulaire à nouveau
    return $this->render('home/edit_car.html.twig', [
        'form' => $form->createView(),
        'user' => $user,
    ]);
}



}
