<?php

namespace App\Controller;

use App\Entity\Asking;
use App\Repository\ShowcaseRepository;
use App\Service\CreditProgram;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/api/evaluate_credit_program', methods: ['GET'])]
    public function define(
        Request $request,
        CreditProgram $creditProgram
    ): Response {
        $requestParams = $request->query->all();

        return $this->json($creditProgram->evaluate(
            $requestParams['price'] ?? null,
            $requestParams['init'] ?? null,
            $requestParams['month'] ?? null,
            $requestParams['term'] ?? null,
        ));
    }

    #[Route('/api/credit_asking')]
    public function save_credit_asking(
        Request $request,
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
    ): Response {
        $requestParams = $request->request->all();
        try {
            $asking = new Asking;
            $asking->setShowcaseId((int)$requestParams['showcase_id'])
                ->setProgrammAmount((float)$requestParams['programm_amount'])
                ->setRate((float)$requestParams['rate'])
                ->setVehiclePrice((float)$requestParams['vehicle_price'])
                ->setInitialPayment((int)$requestParams['initial_payment'])
                ->setMonthlyPayment((float)$requestParams['monthly_payment'])
                ->setCreditTerm((int)$requestParams['credit_term'])
                ->setCreatedAt(time());

            $errors = $validator->validate($asking);
            if (count($errors) > 0) {
                return $this->json((string)$errors);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($asking);
            $entityManager->flush();

        } catch (\ErrorException $e) {
            return $this->json($e->getMessage());
        }
        
        return $this->json(['result' => 'success']);
    }
}
