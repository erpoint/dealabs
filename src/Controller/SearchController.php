<?php

namespace App\Controller;

use App\Entity\Deal;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route("/searchAll", name:"app_search_all_deal")]
    public function searchAllDealAction(Request $request, EntityManagerInterface $entityManager)
    {
        $searchData = $request->query->get('textInput');

        $deals = $entityManager->getRepository(Deal::class)->matching(
            Criteria::create()
                ->where(Criteria::expr()->contains('title', $searchData))
                ->orWhere(Criteria::expr()->contains('description', $searchData))
                //->orWhere(Criteria::expr()->contains('owner.login', $searchData))
        );

        return $this->render('root/index.html.twig', [
            'deals' => $deals,
            'tabSelected' => 'all deals'
        ]);
    }

    #[Route("/searchALaUne", name:"app_search_a_la_une_deal")]
    public function searchALaUneDealAction(Request $request, EntityManagerInterface $entityManager)
    {
        $searchData = $request->query->get('textInput');

        $deals = $entityManager->getRepository(Deal::class)->matching(
            Criteria::create()
                ->where(Criteria::expr()->contains('title', $searchData))
                ->orWhere(Criteria::expr()->contains('description', $searchData))
                ->andWhere(Criteria::expr()->gte('degres', 100))
                //->orWhere(Criteria::expr()->contains('owner.login', $searchData))
        );

        return $this->render('root/index.html.twig', [
            'deals' => $deals,
            'tabSelected' => 'a la une'
        ]);
    }
}
