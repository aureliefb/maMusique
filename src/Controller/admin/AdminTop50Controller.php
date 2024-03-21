<?php

namespace App\Controller\admin;

use App\Entity\Top50;
use App\Form\Top50Type;
use App\Repository\Top50Repository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTop50Controller extends AbstractController
{
    #[Route('/admin/top50', name: 'admin_top50', methods: ['GET'])]
    public function index(Top50Repository $repoTop, PaginatorInterface $page, Request $req): Response
    {
        $qry_top = $repoTop->listAllTitres();
        $nbTitres = $repoTop->countAllTitres();

        $titres_top = $page->paginate(
            $qry_top,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_top50/index.html.twig', [
            'titres' => $titres_top,
            'nbTitres' => $nbTitres[0][1]
        ]);
    }

    #[Route('/admin/top50/add', name: 'admin_add_title', methods: ['GET', 'POST'])]
    #[Route('/admin/top50/edit/{id}', name: 'admin_edit_title', methods: ['GET', 'POST'])]
    public function addEditTitle(Top50 $top = null, Request $req, EntityManagerInterface $em): Response
    {
        if($top == null) {
            $top = new Top50();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(Top50Type::class, $top);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($top);
            $em->flush();
            $this->addFlash('success', 'Titre "'.$top->getTitre().'" '.$mode.' !');
            return $this->redirectToRoute('admin_top50');
        }

        return $this->render('admin/admin_top50/addEdit.html.twig', [
            'formTop50' => $form->createView()
        ]);
    }


    #[Route('/admin/top50/delete/{id}', name: 'admin_delete_title', methods: ['GET'])]
    public function deleteTitle(Top50 $top, EntityManagerInterface $em): Response
    {
        $em->remove($top);
        $em->flush();
        $this->addFlash('success', 'Titre "'.$top->getTitre().'" supprimé !');
        return $this->redirectToRoute('admin_top50');    }
}
