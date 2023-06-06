<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RootController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    public function index(DealRepository $dealRepository): Response
    {
        $deals = $dealRepository->findAll();

        return $this->render('root/index.html.twig', [
            'deals' => $deals,
        ]);
    }
}
