<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MyAccountType;
use App\Form\PasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    #[Route('/account', name: 'app_my_account')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $formAccount = $this->createForm(MyAccountType::class, $user);
        $formAccount->handleRequest($request);
        $formPassword = $this->createForm(PasswordType::class, $user);
        $formPassword->handleRequest($request);
        if($formAccount->isSubmitted() && $formAccount->isValid()) {
            $user->setLogin($formAccount->get('login')->getData());
            $user->setEmail($formAccount->get('email')->getData());
        }
        if($formPassword->isSubmitted() && $formPassword->isValid()) {
            if ($formPassword->get('password')->getData()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $formPassword->get('password')->getData()
                    )
                );
            }
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->render('my_account/index.html.twig', [
            'controller_name' => 'MyAccountController',
            'accountForm' => $formAccount->createView(),
            'passwordForm' => $formPassword->createView()
        ]);
    }
}
