<?php

namespace App\Controller;

use App\Entity\Deal;
use App\Entity\User;
use App\Form\CreateDealFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DealController extends AbstractController
{
    #[Route('/create-deal', name: 'app_create_deal')]
    public function createDeal(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreateDealFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal = new Deal();
            $deal->setLink($form->get("link")->getData());
            $deal->setFullPrice($form->get("fullPrice")->getData());
            $deal->setCurrentPrice($form->get("currentPrice")->getData());
            $deal->setDegres($form->get("degres")->getData());
            $deal->setTitle($form->get("title")->getData());
            $deal->setDescription($form->get("description")->getData());
            $deal->setImage($form->get("image")->getData());

            $deal->setCreatedAt(new \DateTimeImmutable());
            $user = $entityManager->getRepository(User::class)->findOneBy(array('email' => $this->getUser()->getUserIdentifier()));
            $deal->setOwner($user);
            $user->getDeals()->add($deal);

            $entityManager->persist($deal);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('deal/create-deal.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
