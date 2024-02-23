<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ArtisteRepository;
use App\Repository\ConcertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil', methods: ['GET'])]
    public function index(ArtisteRepository $repoArtist, AlbumRepository $repoAlbum, ConcertRepository $repoConcert): Response
    {
        $nbArtists = count($repoArtist->findAll());
        $nbAlbums = count($repoAlbum->findAll());
        $nbConcerts = count($repoConcert->findAll());
        return $this->render('accueil/index.html.twig', [
            'nbArtists' => $nbArtists,
            'nbAlbums' => $nbAlbums,
            'nbConcerts' => $nbConcerts
        ]);
    }

    
}
