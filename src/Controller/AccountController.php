<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account_signup")
     */
    public function signup(): Response
    {
        $user = new User();
        $accountForm = $this->createForm(UserType::class, $user);

        return $this->render('account/signup.html.twig', [
            'accountForm' => $accountForm->createView()
        ]);
    }
}
