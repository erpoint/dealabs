<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Form\AddCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DealDetailController extends AbstractController
{

    #[Route('/deal/{id}', name: 'app_deal_detail')]
    public function index(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $deal = $em->getRepository(Deal::class)->find($id);
        $comment = new Comment();
        $addCommentForm = $this->createForm(AddCommentType::class, $comment);
        $addCommentForm->handleRequest($request);
        if($addCommentForm->isSubmitted() && $addCommentForm->isValid()){
            //dd($request->request);

            $comment = (new Comment())
                ->setTheuser($this->getUser())
                ->setDeal($deal)
                ->setContent($addCommentForm->get("content")->getData())
                ->setCreatedAt(new \DateTimeImmutable());
            $em->getRepository(Comment::class)->save($comment, true);
        }
        return $this->render('deal_detail/deal_detail.html.twig', [
            'controller_name' => 'DealDetailController',
            'deal' => $deal,
            'addCommForm'=>$addCommentForm
        ]);
    }
}
