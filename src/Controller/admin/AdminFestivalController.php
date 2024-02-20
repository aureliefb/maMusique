<?php

namespace App\Controller\admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminFestivalController extends AbstractController
{
    #[Route('/admin/festival', name: 'admin_festival', methods: ['GET'])]
    public function index(FestivalRepository $repoFestival, PaginatorInterface $page, Request $req): Response
    {
        $qry_festivals = $repoFestival->listAllFestivals();
        $nbFestivals = $repoFestival->countAllFestivals();

        $festivals = $page->paginate(
            $qry_festivals,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_festival/index.html.twig', [
            'festivals' => $festivals,
            'nbFestivals' => $nbFestivals[0][1]
        ]);
    }

    #[Route('/admin/festival/add', name: 'admin_add_festival', methods: ['GET', 'POST'])]
    #[Route('/admin/festival/edit/{id}', name: 'admin_edit_festival', methods: ['GET', 'POST'])]
    public function addEditFestival(Festival $festival = null, Request $req, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if($festival == null) {
            $festival = new Festival();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(FestivalType::class, $festival);
        //dump($festival->getLieu()->getVille());
        dump($festival);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            $afficheFile = $form->get('image')->getData();
            if($afficheFile) {
                $safeFilename = $slugger->slug($afficheFile);
                $filename = $safeFilename.'-'.uniqid().'.'.$afficheFile->guessExtension();
                try {
                    // $dir = '../../public/images/albums/';
                    // $pochetteFile->move($dir, $newFilename);
                    $afficheFile->move(
                        $this->getParameter('festivals_img_dir'), // voir dans config/services.yaml
                        $filename
                    );
                } catch (FileException $e) {
                    dump('erreur chargement image pochette');
                    // ... handle exception if something happens during file upload
                }
                $festival->setImage($filename);
            }
            $em->persist($festival);
            $em->flush();
            $this->addFlash('success', 'Festival "'.$festival->getNomFestival().'" '.$mode.' !');
            if($_GET['source'] === 'formAddConcert') {
                return $this->redirectToRoute('admin_add_concert');
            } else {
                return $this->redirectToRoute('admin_festival');
            }
        }
        return $this->render('admin/admin_festival/addEdit.html.twig', [
            'formFestival' => $form->createView(),
            'affiche' => $festival->getImage(),
            'nom' => $festival->getNomFestival()
        ]);
    }

    #[Route('/admin/festival/delete/{id}', name: 'admin_delete_festival', methods: ['GET'])]
    public function deleteFestival(Festival $festival, EntityManagerInterface $em): Response
    {
        /*$nbConcerts = $concert->getConcerts()->count();
        $nbArtistes = $artist->getAlbums()->count();*/

        /* if($nbAlbums > 0) {
             $this->addFlash('danger', 'Suppression impossible : '.$nbAlbums.' albums associés');
         } elseif($nbConcerts > 0) {
             $this->addFlash('danger', 'Suppression impossible : '.$nbConcerts.' concerts associés');
         } else {*/
        $em->remove($festival);
        $em->flush();
        $this->addFlash('success', 'Festival "'.$festival->getNomFestival().'" supprimé !');
        //}
        return $this->redirectToRoute('admin_festival');
    }
}
