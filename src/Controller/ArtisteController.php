<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Repository\ArtisteRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtisteController extends AbstractController
{
    #[Route('/artistes', name: 'artistes', methods: ['GET'])]
    public function index(ArtisteRepository $repo, PaginatorInterface $page, Request $request): Response
    {
        //$artistes = $repo->findBy([], ['nom'=>'ASC']);
        $qry_all_artists = $repo->listAllArtists();
        $nbArtists = $repo->countAllArtists();
        $all_artistes = $page->paginate(
            $qry_all_artists,
            $request->query->getInt('page', 1),
            20);
        return $this->render('artiste/listeArtistes.html.twig', [
            'artistes' => $all_artistes,
            'nbArtists' => $nbArtists[0][1]
        ]);
    }

    #[Route('/artiste/{id}', name: 'ficheArtiste', methods: ['GET'])]
    public function ficheArtiste(Artiste $artiste): Response
    {
        return $this->render('artiste/ficheArtiste.html.twig', [
            'artist' => $artiste
        ]);
    }

}
