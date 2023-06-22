<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Form\AddCommentType;
use App\Form\LikeType;
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
        $likeForm = $this->createForm(LikeType::class, $deal);
        $likeForm->handleRequest($request);
        if($addCommentForm->isSubmitted() && $addCommentForm->isValid()){
            $comment = (new Comment())
                ->setTheuser($this->getUser())
                ->setDeal($deal)
                ->setContent($addCommentForm->get("content")->getData())
                ->setCreatedAt(new \DateTimeImmutable());
            $em->getRepository(Comment::class)->save($comment, true);
        }
        if($likeForm->isSubmitted() && $likeForm->isSubmitted()){
            if($likeForm->get('degres_up')->isClicked()){
                $deal->setDegres($deal->getDegres()+1);
            }elseif($likeForm->get('degres_down')->isClicked()) {
                $deal->setDegres($deal->getDegres()-1);
            }
            $em->getRepository(Deal::class)->save($deal, true);
        }
        return $this->render('deal_detail/deal_detail.html.twig', [
            'controller_name' => 'DealDetailController',
            'deal' => $deal,
            'addCommForm'=>$addCommentForm,
            'likeForm'=>$likeForm,
        ]);
    }
}
