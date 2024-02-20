<?php

namespace App\Controller\admin;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPaysController extends AbstractController
{
    #[Route('/admin/pays', name: 'admin_pays', methods: ['GET'])]
    public function index(PaysRepository $repoPays, PaginatorInterface $page, Request $req): Response
    {
        $qry_styles = $repoPays->listAllPays();
        $nbPays = $repoPays->countAllPays();
        $list_pays = $page->paginate(
            $qry_styles,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_pays/index.html.twig', [
            'list_pays' => $list_pays,
            'nbPays' => $nbPays[0][1]
        ]);

    }

    #[Route('/admin/pays/add', name: 'admin_add_pays', methods: ['GET', 'POST'])]
    #[Route('/admin/pays/edit/{id}', name: 'admin_edit_pays', methods: ['GET', 'POST'])]
    public function addEditPays(Pays $pays = null, Request $req, EntityManagerInterface $em): Response
    {
        if($pays == null) {
            $pays = new Pays();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(PaysType::class, $pays);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            dump($pays);
            $em->persist($pays);
            $em->flush();
            $this->addFlash('success', 'Pays "'.$pays->getPays().'" '.$mode.' !');
            if($_GET['source'] === 'formAddArtist') {
                return $this->redirectToRoute('admin_add_artist');
            } else {
                return $this->redirectToRoute('admin_pays');
            }
        }
        return $this->render('admin/admin_pays/addEdit.html.twig', [
            'formPays' => $form->createView(),
            //'source' => $_GET['source'] ?? ''
        ]);
    }

    #[Route('/admin/pays/delete/{id}', name: 'admin_delete_pays', methods: ['GET'])]
    public function deletePays(Pays $pays, EntityManagerInterface $em): Response
    {
        $em->remove($pays);
        $em->flush();
        $this->addFlash('success', 'Pays "'.$pays->getPays().'" supprimé !');
        return $this->redirectToRoute('admin_pays');
    }
}
