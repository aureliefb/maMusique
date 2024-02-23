<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    #[Route('/albums', name: 'albums', methods: ['GET'])]
    public function index(AlbumRepository $repo, PaginatorInterface $page, Request $request): Response
    {
        $qry_all_albums = $repo->listAllAlbums();
        $nbAlbums = $repo->countAllAlbums();
        $all_albums = $page->paginate(
            $qry_all_albums,
            $request->query->getInt('page', 1),
            20);

        return $this->render('album/listeAlbums.html.twig', [
            'albums' => $all_albums,
            'nbAlbums' => $nbAlbums[0][1]
        ]);
    }


    #[Route('/album/{id}', name: 'ficheAlbum', methods: ['GET'])]
    public function ficheAlbum(Album $album): Response
    {
        return $this->render('album/ficheAlbum.html.twig', [
            'album' => $album
        ]);
    }




}
