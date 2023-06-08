<?php

namespace App\Controller;

use App\Entity\Ride;
use App\Entity\Car;
use App\Entity\User;
use App\Entity\Rule;
use App\Form\UserProfileType;
use App\Form\UserCarType;
use App\Form\UserRuleType;
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
        $ride = $repository->findOneBy(["driver" => $user]);

        $repository = $entityManagerInterface->getRepository(Car::class);
        $car = $repository->findOneBy(["owner" => $user]);

        $repository = $entityManagerInterface->getRepository(Rule::class);
        $rule = $repository->findOneBy(["author" => $user]);

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
    // EDIT PROFIL CAR
    #[Route('/profil/editCar', name: 'app_profil_editCar')]
    public function editCar(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
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

            $car->setCreated(new \DateTime);
            $car->setOwner($user);
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









    // CREATE PROFIL CAR
    #[Route('/profil/createCar', name: 'app_profil_createCar')]
    public function createCar(Request $request, EntityManagerInterface $entityManager): Response
    {
         /** @var User $user */
         $user = $this->getUser();
        //  $car = $user->getCar(); // Récupérez la voiture liée à l'utilisateur

        //  if (!$car) {
        //      $user->setCar($car); // Liez la voiture à l'utilisateur
        //  }



        $car = new Car(); // Créez une nouvelle instance de Car s'il n'y a pas de voiture liée

        // Créez une instance de votre nouveau formulaire UserCarType en utilisant la voiture
        $form = $this->createForm(UserCarType::class, $car);

        // Soumettez le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $car->setCreated(new \DateTime);
            $car->setOwner($user);
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
        ]);
    }












    //////////////////////
    // EDIT PROFIL RULE
    #[Route('/profil/editRule', name: 'app_profil_editRule')]
    public function editRule(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $rule = $entityManager->getRepository(Rule::class)->findOneBy(["author" => $user]); // Récupérez les règles liées à l'utilisateur

        // Créez une instance de votre nouveau formulaire UserRuleType en utilisant la voiture
        $form = $this->createForm(UserRuleType::class, $rule);

        // Soumettez le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le formulaire est valide, vous pouvez mettre à jour les informations des règles
            /** @var Rule $rule */
            $rule = $form->getData();
            $rule->setAuthor($user);
            
            $entityManager->persist($rule); // Persistez les règles dans l'EntityManager
            $entityManager->flush();

            // Redirigez l'utilisateur vers la page du profil avec un message de succès
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_profil');
        }

        // Si le formulaire n'est pas soumis ou n'est pas valide, affichez le formulaire à nouveau
        return $this->render('home/edit_rule.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
