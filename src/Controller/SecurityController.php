<?php 

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $email = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();

        $loginForm = $this->createForm(LoginType::class, ['email' => $email]);

        return $this->render('security/login.html.twig', [
            'loginForm' => $loginForm->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }
}