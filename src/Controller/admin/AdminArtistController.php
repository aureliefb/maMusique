<?php

namespace App\Controller\admin;

use App\Entity\Artiste;
use App\Form\ArtistType;
use App\Repository\ArtisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class AdminArtistController extends AbstractController
{
    public $_filesystem;
    public function __construct() {
        $this->_filesystem = new Filesystem();
    }


    #[Route('/admin/artists', name: 'admin_artists', methods: ['GET'])]
    public function index(ArtisteRepository $repoArtists, PaginatorInterface $page, Request $req): Response
    {
        //$artists = $repoArtists->findAll();
        $qry_artists = $repoArtists->listAllArtists();
        $nbArtists = $repoArtists->countAllArtists();

        $artists = $page->paginate(
            $qry_artists,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_artists/index.html.twig', [
            'artists' => $artists,
            'nbArtists' => $nbArtists[0][1]
        ]);
    }

    #[Route('/admin/artists/add', name: 'admin_add_artist', methods: ['GET', 'POST'])]
    #[Route('/admin/artists/edit/{id}', name: 'admin_edit_artist', methods: ['GET', 'POST'])]
    public function addEditArtist(Artiste $artist = null, Request $req, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if($artist === null) {
            $artist = new Artiste();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $dirFiles = $this->getParameter('artists_img_dir');

            $currentPhoto = $artist->getImage(); // img existante
            if($currentPhoto && $this->_filesystem->exists($dirFiles.'/'.$currentPhoto)) {
                try {
                    $this->_filesystem->remove($dirFiles.'/'.$currentPhoto);
                } catch(IOExceptionInterface $e) {
                    dump('photo actuelle non supprimée');
                }
            } else {
                dump('pas de photo existante');
            }

            $photoArtist = $form->get('image')->getData(); // nvle img
            if ($photoArtist) {
                dump($photoArtist);
                $safeFileName = $slugger->slug($photoArtist);
                $fileName = $safeFileName . '-' . uniqid() . '.' . $photoArtist->guessExtension();
                try { // img déplacée dans répertoire "artists"
                    // $dir = '../../public/images/artists/';
                    // $pochetteFile->move($dir, $newFilename);
                    $photoArtist->move(
                        $dirFiles, // voir dans config/services.yaml
                        $fileName
                    );
                } catch (IOExceptionInterface $e) {
                    dump('Erreur chargement photo artiste');
                    // ... handle exception if something happens during file upload
                }
                $artist->setImage($fileName);

                $em->persist($artist);
                $em->flush();
                $this->addFlash('success', 'Artiste "' . $artist->getNom() . '" ' . $mode . ' !');
                /*if ($_GET && $_GET['source'] === 'formAddConcert') {
                    return $this->redirectToRoute('admin_add_concert');
                } else {
                    return $this->redirectToRoute('admin_artists');
                }*/
                return $this->redirectToRoute('admin_artists');

                // si img existante trouvée
                /*if(file_exists($dirFiles.'/'.$currentPhoto)) {
                    // suppression img existante
                    if(unlink($dirFiles.'/'.$currentPhoto)) {
                        $safeFileName = $slugger->slug($photoArtist);
                        $fileName = $safeFileName . '-' . uniqid() . '.' . $photoArtist->guessExtension();
                        try {
                            // $dir = '../../public/images/artists/';
                            // $pochetteFile->move($dir, $newFilename);
                            $photoArtist->move(
                                $dirFiles, // voir dans config/services.yaml
                                $fileName
                            );
                        } catch (FileException $e) {
                            dump('erreur chargement photo artiste');
                            // ... handle exception if something happens during file upload
                        }
                        $artist->setImage($fileName);

                        $em->persist($artist);
                        $em->flush();
                        $this->addFlash('success', 'Artiste "' . $artist->getNom() . '" ' . $mode . ' !');
                        if ($_GET && $_GET['source'] === 'formAddConcert') {
                            return $this->redirectToRoute('admin_add_concert');
                        } else {
                            return $this->redirectToRoute('admin_artists');
                        }
                    } else {
                        dump('suppression fichier KO');
                    }
                } else {
                    dump('fichier pas trouvé');
                }*/
            } else {
                dump('pas de photo à charger');
            }
        }

        return $this->render('admin/admin_artists/addEdit.html.twig', [
            'formArtiste' => $form->createView(),
            'photo' => $artist->getImage(),
            'nom' => $artist->getNom(),
            //'source' => $_GET['source']
        ]);
    }

    #[Route('/admin/artists/delete/{id}', name: 'admin_delete_artist', methods: ['GET'])]
    public function deleteArtist(Artiste $artist, EntityManagerInterface $em): Response
    {
        $nbAlbums = $artist->getAlbums()->count();
        $nbConcerts = $artist->getConcerts()->count();
        if($nbAlbums > 0) {
            $this->addFlash('danger', 'Suppression impossible : '.$nbAlbums.' albums associés');
        } elseif($nbConcerts > 0) {
            $this->addFlash('danger', 'Suppression impossible : '.$nbConcerts.' concerts associés');
        } else {
            $dirFiles = $this->getParameter('artists_img_dir');

            if($artist->getImage()) {
                try {
                    $this->_filesystem->remove($dirFiles.'/'.$artist->getImage());
                } catch(IOExceptionInterface $e) {
                    dump('photo actuelle non supprimée');
                }
            } else {
                dump('pas d\'image');
            }

            $em->remove($artist);
            $em->flush();
            $this->addFlash('success', 'Artiste "'.$artist->getNom().'" supprimé !');
        }
        return $this->redirectToRoute('admin_artists');
    }

}
