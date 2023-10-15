<?php

namespace App\Controller\admin;

use App\Repository\ArtisteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminArtistController extends AbstractController
{
    #[Route('/admin/artists', name: 'admin_artists', methods: ['GET'])]
    public function index(ArtisteRepository $repoArtists): Response
    {
        $artists = $repoArtists->findAll();
        $test = 'test';
        return $this->render('admin/admin_artists/index.html.twig', [
            'artists' => $artists
        ]);
    }

    #[Route('/admin/artists/edit/{id}', name: 'admin_edit_artist', methods: ['GET', 'UPDATE'])]
    public function edit(): Response
    {
        return $this->render('admin/admin_artists/edit.html.twig', [

        ]);
    }
}
