<?php

namespace App\Controller;

use Carbon\Carbon;
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
        $getAllConcerts = $repo->listAllConcerts('userConcerts');
        dump($getAllConcerts);

        foreach($getAllConcerts as $concert) {
            $id = $concert->getId();
            $artist = $concert->getArtiste()->getNom();
            $ville = $concert->getLieu()->getVille();
            $lieu = $concert->getLieu()->getNom();
            $latitude = $concert->getLieu()->getLatitude();
            $longitude = $concert->getLieu()->getLongitude();
            $date = Carbon::createFromFormat('Y-m-d', $concert->getDateConcert(), 'Europe/Paris')->format('d/m/Y');
            $festival = [];
            if($concert->getFestival() !== null) {
                //dump($concert->getFestival());
                $festival['nom'] = $concert->getFestival()->getNomFestival() ?? null;
                $festival['img'] = $concert->getFestival()->getImage() ?? null;
                $festival['date_deb'] = $concert->getFestival()->getDateStart() ?? null;
                $festival['date_fin'] = $concert->getFestival()->getDateEnd() ?? null;
                $festival['lieu'] = $lieu;
                $festival['ville'] = $ville;
            }
            $img_concert = $concert->getImage() ?? null;
            $allConcerts[] = [
                'id' => $id,
                'artiste' => $artist,
                'ville' => $ville,
                'lieu' => $lieu,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'date' => $date,
                'festival' => $festival,
                'img_concert' => $img_concert
            ];
        }
        //dump($allConcerts);

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
