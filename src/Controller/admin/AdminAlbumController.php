<?php

namespace App\Controller\admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminAlbumController extends AbstractController
{
    #[Route('/admin/album', name: 'admin_albums', methods: ['GET'])]
    public function index(AlbumRepository $repoAlbum, PaginatorInterface $page, Request $req): Response
    {
        $qry_album = $repoAlbum->listAllAlbums();
        $nbAlbums = $repoAlbum->countAllAlbums();
        $list_albums = $page->paginate(
            $qry_album,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_album/index.html.twig', [
            'albums' => $list_albums,
            'nbAlbums' => $nbAlbums[0][1]
        ]);
    }

    #[Route('/admin/album/add', name: 'admin_add_album', methods: ['GET', 'POST'])]
    #[Route('/admin/album/edit/{id}', name: 'admin_edit_album', methods: ['GET', 'POST'])]
    public function addEditAlbum(Album $album = null, Request $req, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if($album == null) {
            $album = new Album();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            dump($album);
            $pochetteFile = $form->get('image')->getData();
            if($pochetteFile) {
                $safeFilename = $slugger->slug($pochetteFile);
                $filename = $safeFilename.'-'.uniqid().'.'.$pochetteFile->guessExtension();
                try {
                    // $dir = '../../public/images/albums/';
                    // $pochetteFile->move($dir, $newFilename);
                    $pochetteFile->move(
                        $this->getParameter('albums_img_dir'), // voir dans config/services.yaml
                        $filename
                    );
                } catch (FileException $e) {
                    dump('erreur chargement image pochette');
                    // ... handle exception if something happens during file upload
                }
                $album->setImage($filename);
            }
            $em->persist($album);
            $em->flush();
            $this->addFlash('success', 'Album "'.$album->getNom() .'" '. $mode.' !');
            return $this->redirectToRoute('admin_albums');
        }

        return $this->render('admin/admin_album/addEdit.html.twig', [
            'formAlbum' => $form->createView(),
            'pochette' => $album->getImage()
        ]);
    }

    #[Route('/admin/album/delete/{id}', name: 'admin_delete_album', methods: ['GET'])]
    public function deleteAlbum(Album $album, EntityManagerInterface $em): Response
    {
        $em->remove($album);
        $em->flush();
        $this->addFlash('success', 'Album "'.$album->getNom().'" supprimé !');
        return $this->redirectToRoute('admin_albums');
    }


}
