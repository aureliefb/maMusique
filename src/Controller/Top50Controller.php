<?php

namespace App\Controller;

use App\Repository\Top50Repository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Top50Controller extends AbstractController
{
    #[Route('/top50', name: 'top50', methods: ['GET'])]
    public function index(Top50Repository $repo, PaginatorInterface $page, Request $request): Response
    {
        $qry_top = $repo->listAllTitres();
        $nbTitres = $repo->countAllTitres();

        $titres_top = $page->paginate(
            $qry_top,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('top50/index.html.twig', [
            'titres' => $titres_top,
            'nbTitres' => $nbTitres[0][1]
        ]);
    }



}
