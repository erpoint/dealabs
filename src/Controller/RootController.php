<?php

namespace App\Controller;

use App\Repository\DealRepository;
use Doctrine\Common\Collections\Criteria;
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
            'tabSelected' => 'all deals'
        ]);
    }

    #[Route('/a-la-une', name: 'app_a_la_une')]
    public function aLaUne(DealRepository $dealRepository): Response
    {
        $deals = $dealRepository->matching(Criteria::create()->where(Criteria::expr()->gte('degres', 100)));

        return $this->render('root/index.html.twig', [
            'deals' => $deals,
            'tabSelected' => 'a la une'
        ]);
    }
}
