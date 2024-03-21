<?php

namespace App\Controller\admin;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminLieuController extends AbstractController
{
    #[Route('/admin/lieu', name: 'admin_lieux', methods: ['GET'])]
    public function index(LieuRepository $repoLieux, PaginatorInterface $page, Request $req): Response
    {
        $qry_lieux = $repoLieux->listAllLieux();
        $nbLieux = $repoLieux->countAllLieux();

        $lieux = $page->paginate(
            $qry_lieux,
            $req->query->getInt('page', 1),
            10
        );

        return $this->render('admin/admin_lieu/index.html.twig', [
            'lieux' => $lieux,
            'nbLieux' => $nbLieux[0][1]
        ]);
    }

    #[Route('/admin/lieu/add', name: 'admin_add_lieu', methods: ['GET', 'POST'])]
    #[Route('/admin/lieu/edit/{id}', name: 'admin_edit_lieu', methods: ['GET', 'POST'])]
    public function addEditLieu(Lieu $lieu = null, Request $req, EntityManagerInterface $em): Response
    {
        if($lieu == null) {
            $lieu = new Lieu();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($lieu);
            $em->flush();
            $this->addFlash('success', 'Lieu "'.$lieu->getNom().'" '.$mode.' !');
            /*if($_GET['source'] === 'formAddConcert') {
                return $this->redirectToRoute('admin_add_concert');
            } else if($_GET['source'] === 'formAddFestival') {
                return $this->redirectToRoute('admin_add_festival');
            } else {*/
                return $this->redirectToRoute('admin_lieux');
            //}

        }
        return $this->render('admin/admin_lieu/addEdit.html.twig', [
            'formLieu' => $form->createView(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude()
        ]);
    }

    #[Route('/admin/lieux/delete/{id}', name: 'admin_delete_lieu', methods: ['GET'])]
    public function deleteLieu(Lieu $lieu, EntityManagerInterface $em): Response
    {
        /*$nbConcerts = $concert->getConcerts()->count();
        $nbArtistes = $artist->getAlbums()->count();*/

        /* if($nbConcerts > 0) {
             $this->addFlash('danger', 'Suppression impossible : '.$nbConcerts.' concerts associés');
         } else {*/
            $em->remove($lieu);
            $em->flush();
            $this->addFlash('success', 'Lieu "'.$lieu->getNom().'" supprimé !');
        //}
        return $this->redirectToRoute('admin_lieux');
    }


    #[Route('/admin/lieu/generate/{id}/{adresse1}/{codepostal}/{ville}', name: 'generate_coordo', methods: ['GET', 'POST'])]
    public function generateCoordo(Lieu $lieu, $id, $adresse1, $codepostal, $ville, EntityManagerInterface $em): Response
    {
        $ligneAdresse = $adresse1.', '.$codepostal.' '.$ville;
        $url = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?outSr=4326&forStorage=false&outFields=*&maxLocations=20&singleLine=".urlencode($ligneAdresse)."&f=json";
        $json = file_get_contents($url);

        $APIcall = json_decode($json);
        if($APIcall) {
            foreach($APIcall->candidates as $api) {
                $x = $api->location->y; // latitude
                $y = $api->location->x; // longitude
            }
        } else {
            dump('problème');
        }
        $lieu->setLatitude($x);
        $lieu->setLongitude($y);

        $em->persist($lieu);
        $em->flush();
        $this->addFlash('success', $lieu->getNom().' - coordonnées calculées et sauvegardées !');
        return $this->redirectToRoute('admin_lieux');
    }




}
