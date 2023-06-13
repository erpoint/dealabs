<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealDetailController extends AbstractController
{

    #[Route('/deal/{id}', name: 'app_deal_detail')]
    public function index(EntityManagerInterface $em, int $id): Response
    {
        $deal = $em->getRepository(Deal::class)->find($id);

        return $this->render('deal_detail/deal_detail.html.twig', [
            'controller_name' => 'DealDetailController',
            'deal' => $deal,
        ]);
    }
}
