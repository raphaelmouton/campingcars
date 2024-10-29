<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AnnoncesRepository;

class BasicPageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AnnoncesRepository $annoncesRepository): Response
    {
        $annonces = $annoncesRepository->findBy(["ACTIVE" => 1],["id" => "DESC"], $limit = 8);

        return $this->render('basic_page/index.html.twig', [
            'controller_name' => 'BasicPageController',
            'annonces' => $annonces,
        ]);
    }
}
