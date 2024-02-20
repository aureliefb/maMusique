<?php

namespace App\Controller\admin;

use App\Entity\Style;
use App\Form\StyleType;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStyleController extends AbstractController
{
    #[Route('/admin/styles', name: 'admin_styles', methods: ['GET'])]
    public function index(StyleRepository $repoStyle, PaginatorInterface $page, Request $req): Response
    {
        $qry_styles = $repoStyle->listAllStyles();
        $nbStyles = $repoStyle->countAllStyles();
        $styles = $page->paginate(
            $qry_styles,
            $req->query->getInt('page', 1),
            10
        );
        return $this->render('admin/admin_style/index.html.twig', [
            'styles' => $styles,
            'nbStyles' => $nbStyles[0][1]
        ]);
    }

    #[Route('/admin/style/add', name: 'admin_add_style', methods: ['GET', 'POST'])]
    #[Route('/admin/style/edit/{id}', name: 'admin_edit_style', methods: ['GET', 'POST'])]
    public function addEditStyle(Style $style = null, Request $req, EntityManagerInterface $em): Response
    {
        if($style == null) {
            $style = new Style();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(StyleType::class, $style);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            dump($style);
            $em->persist($style);
            $em->flush();
            $this->addFlash('success', 'Style "'.$style->getStyle().'" '.$mode.' !');
            if($_GET && $_GET['source'] === 'formAddArtist') {
                return $this->redirectToRoute('admin_add_artist');
            } else {
                return $this->redirectToRoute('admin_styles');
            }

        }
        return $this->render('admin/admin_style/addEdit.html.twig', [
            'formStyle' => $form->createView(),
            //'source' => $_GET['source'] ?? ''
        ]);
    }

    #[Route('/admin/style/delete/{id}', name: 'admin_delete_style', methods: ['GET'])]
    public function deleteStyle(Style $style, EntityManagerInterface $em): Response
    {
        $em->remove($style);
        $em->flush();
        $this->addFlash('success', 'Style "'.$style->getStyle().'" supprimé !');
        return $this->redirectToRoute('admin_styles');
    }


}
