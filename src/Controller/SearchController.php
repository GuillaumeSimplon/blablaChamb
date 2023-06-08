<?php

namespace App\Controller;

use App\Entity\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $manager, Request $request): Response
    {
        $titleParam = $request->query->get('search');

        $repoSearch = $manager->getRepository(Search::class);

        $search = $repoSearch->findByAvailable(true, $titleParam);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'SearchController',
            'search' => $search,
        ]);
    }
}
