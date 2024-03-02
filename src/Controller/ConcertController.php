<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Repository\ConcertRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConcertController extends AbstractController
{

    #[Route('/concerts', name: 'concerts', methods: ['GET'])]
    public function index(ConcertRepository $repo, PaginatorInterface $page, Request $request): Response
    {
        $allConcerts = [];
        $getAllConcerts = $repo->listAllConcerts();
        //dump($getAllConcerts);

        foreach($getAllConcerts as $concert) {
            $id = $concert->getId();
            $artist = $concert->getArtiste()->getNom();
            $ville = $concert->getLieu()->getVille();
            $lieu = $concert->getLieu()->getNom();
            $date = $concert->getDateConcert();
            $festival = '';
            $img_festival = '';
            if($concert->getFestival() !== NULL) {
                $festival = $concert->getFestival()->getNomFestival();
                $img_festival = $concert->getFestival()->getImage();
            }
            $img_concert = '';
            if($concert->getImage() != NULL) {
                $img_concert = $concert->getImage();
            }
            $allConcerts[] = [
                'id' => $id,
                'artiste' => $artist,
                'ville' => $ville,
                'lieu' => $lieu,
                'date' => $date,
                'festival' => $festival,
                'img_festival '=> $img_festival,
                'img_concert' => $img_concert
            ];
        }
        dump($allConcerts);

        $nbConcerts = $repo->countAllConcerts();
        /*$all_concerts = $page->paginate(
            $allConcerts,
            $request->query->getInt('page', 1),
            10);*/
        return $this->render('concert/listeConcerts.html.twig', [
            //'concerts' => $all_concerts,
            'concerts' => $allConcerts,
            'nbConcerts' => $nbConcerts[0][1]
        ]);
    }


    #[Route('/concert/{id}', name: 'ficheConcert', methods: ['GET'])]
    public function ficheConcert(Concert $concert): Response
    {
        return $this->render('concert/ficheConcert.html.twig', [
            'concert' => $concert
        ]);
    }






}
