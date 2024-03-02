<?php

namespace App\Controller\admin;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminConcertController extends AbstractController
{
    public $_filesystem;
    public function __construct() {
        $this->_filesystem = new Filesystem();
    }

    #[Route('/admin/concert', name: 'admin_concerts')]
    public function index(ConcertRepository $repoConcert, PaginatorInterface $page, Request $req): Response
    {
        $qry_concerts = $repoConcert->listAllConcerts();
        $nbConcerts = $repoConcert->countAllConcerts();
        $list_concerts = $page->paginate(
            $qry_concerts,
            $req->query->getInt('page', 1),
            10
        );

        return $this->render('admin/admin_concert/index.html.twig', [
            'concerts' => $list_concerts,
            'nbConcerts' => $nbConcerts[0][1]
        ]);
    }

    #[Route('/admin/concert/add', name: 'admin_add_concert', methods: ['GET', 'POST'])]
    #[Route('/admin/concert/edit/{id}', name: 'admin_edit_concert', methods: ['GET', 'POST'])]
    public function addEditConcert(Concert $concert = null, Request $req, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if($concert == null) {
            $concert = new Concert();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $nomArtiste = '';
        if($concert->getArtiste() != null) {
            $nomArtiste = $concert->getArtiste()->getNom();
        }

        $form = $this->createForm(ConcertType::class, $concert);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $dirFiles = $this->getParameter('concerts_img_dir');
            $currentPhoto = $concert->getImage();

            $artist = $form->get('artiste')->getData();
            $lieu = $form->get('lieu')->getData();
            //$is_festival = $form->get('is_festival')->getData();
            $festival = $form->get('Festival')->getData();
            $concertImg = $form->get('image')->getData();

            if( (isset($artist) && $artist == null && $artist->getNom() == '') ||
                (isset($lieu) && $lieu == null && $lieu->getNom() == '') ||
                (isset($festival) && $festival == null && $festival->getNomFestival() == '')
            ) {
                dump('choix non fait');
            } else {
                if($currentPhoto && $this->_filesystem->exists($dirFiles.'/'.$currentPhoto)) {
                    try {
                        $this->_filesystem->remove($dirFiles.'/'.$currentPhoto);
                    } catch(IOExceptionInterface $e) {
                        dump('photo actuelle non supprimée');
                    }
                } else {
                    dump('pas de photo existante');
                }

                if($concertImg) {
                    $safeFileName = $slugger->slug($concertImg);
                    $fileName = $safeFileName.'-'.uniqid().'.'.$concertImg->guessExtension();
                    try {
                        // $dir = '../../public/images/artists/';
                        // $pochetteFile->move($dir, $newFilename);
                        $concertImg->move(
                            $dirFiles, // voir dans config/services.yaml
                            $fileName
                        );
                    } catch (IOExceptionInterface $e) {
                        dump('erreur chargement image concert');
                        // ... handle exception if something happens during file upload
                    }
                    $concert->setImage($fileName);
                } else {
                    dump('pas de photo');
                }
                $em->persist($concert);
                $em->flush();
                $this->addFlash('success', 'Concert "'.$nomArtiste.'" '. $mode.' !');
                return $this->redirectToRoute('admin_concerts');
            }
        }

        return $this->render('admin/admin_concert/addEdit.html.twig', [
            'formConcert' => $form->createView(),
            'photo' => $concert->getImage(),
            'nomArtiste' => $nomArtiste
        ]);
    }

    #[Route('/admin/concert/delete/{id}', name: 'admin_delete_concert', methods: ['GET'])]
    public function deleteConcert(Concert $concert, EntityManagerInterface $em): Response
    {
        $dirFiles = $this->getParameter('concerts_img_dir');

        if($concert->getImage()) {
            try {
                $this->_filesystem->remove($dirFiles.'/'.$concert->getImage());
            } catch(IOExceptionInterface $e) {
                dump('photo actuelle non supprimée');
            }
        } else {
            dump('pas d\'image');
        }

        $em->remove($concert);
        $em->flush();
        $this->addFlash('success', 'Concert supprimé !');
        return $this->redirectToRoute('admin_concerts');
    }



}
