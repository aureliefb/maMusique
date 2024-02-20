<?php

namespace App\Controller;

use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil', methods: ['GET'])]
    public function index(ArtisteRepository $repoArtist): Response
    {
        $nbArtists = count($repoArtist->findAll());
        return $this->render('accueil/index.html.twig', [
            'nbArtists' => $nbArtists
        ]);
    }

    
}
