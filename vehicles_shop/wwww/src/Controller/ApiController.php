<?php

namespace App\Controller;

use App\Repository\ShowcaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/showcase', methods: ['GET'])]
    public function showcase(
        Request $request,
        ShowcaseRepository $showcaseRepository,
        ManagerRegistry $doctrine
    ): Response {
        if ($request->query->all() == []) {
            return $this->json($showcaseRepository->getAll($doctrine));
        }
    }
}
