<?php

namespace App\Controller;

use App\Repository\DealRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/api', format: 'json')]
class WeeklyDealApiController extends AbstractFOSRestController
{

    #[Get('/weekly_deals/', name: 'app_weekly_deal_api')]
    public function index(DealRepository $dealRepository): Response
    {
        $criteria = new \Doctrine\Common\Collections\Criteria();
        $criteria->where(\Doctrine\Common\Collections\Criteria::expr()->gt('createdAt', new \DateTimeImmutable('-1 week')));
        $deals = $dealRepository->matching($criteria);
        return $this->handleView(
            $this->view($deals, 200)
        );
    }

    /*
     *
     * Pour s'autentifier y faut:
     *  - aller sur la route /api/longin_check
     *      - En json dans le body:
     *          - "username": "XXX@XXX.XXX"
     *          - "password": "XXXXXXXXXXX"
     *  - en suite quand on fait un GET sur /deals, y faut le header Authentication : Bearer : <token>
     */
    #[Get('/deals', name: 'app_deal_api')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function getDeals(DealRepository $dealRepository){
        $user = $this->getUser();
        $deals = $dealRepository->findBy(['owner'=>$user]);

        return $this->handleView(
            $this->view($deals, 200)
        );
    }
}
