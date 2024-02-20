<?php

namespace App\Controller\admin;

use App\Entity\Support;
use App\Form\SupportType;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSupportController extends AbstractController
{
    #[Route('/admin/support', name: 'admin_support', methods: ['GET'])]
    public function index(SupportRepository $repoSupport, PaginatorInterface $page, Request $req): Response
    {
        $qry_support = $repoSupport->listAllSupports();
        $nbSupports = $repoSupport->countAllSupports();
        $list_supports = $page->paginate(
            $qry_support,
            $req->query->getInt('page', 1),
            10
        );

        return $this->render('admin/admin_support/index.html.twig', [
            'supports' => $list_supports,
            'nbSupports' => $nbSupports[0][1]
        ]);
    }

    #[Route('/admin/support/add', name: 'admin_add_support', methods: ['GET', 'POST'])]
    #[Route('/admin/support/edit/{id}', name: 'admin_edit_support', methods: ['GET', 'POST'])]
    public function addEditPays(Support $support = null, Request $req, EntityManagerInterface $em): Response
    {
        if($support == null) {
            $support = new Support();
            $mode = 'ajouté';
        } else {
            $mode = 'modifié';
        }

        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()) {
            dump($support);
            $em->persist($support);
            $em->flush();
            $this->addFlash('success', 'Support "'.$support->getSupport().'" '.$mode.' !');
            return $this->redirectToRoute('admin_support');
        }
        return $this->render('admin/admin_support/addEdit.html.twig', [
            'formSupport' => $form->createView()
        ]);
    }

    #[Route('/admin/support/delete/{id}', name: 'admin_delete_support', methods: ['GET'])]
    public function deletePays(Pays $support, EntityManagerInterface $em): Response
    {
        $em->remove($support);
        $em->flush();
        $this->addFlash('success', 'Support "'.$support->getSupport().'" supprimé !');
        return $this->redirectToRoute('admin_support');
    }



}
