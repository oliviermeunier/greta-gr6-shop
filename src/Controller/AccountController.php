<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $manager)
    {
        $this->hasher = $hasher;
        $this->manager = $manager;
    }

    /**
     * @Route("/account", name="account_signup")
     */
    public function signup(Request $request): Response
    {
        $user = new User();
        $accountForm = $this->createForm(UserType::class, $user);
        $accountForm->handleRequest($request);

        // Si le formulaire est soumis et valide...
        if ($accountForm->isSubmitted() && $accountForm->isValid()) {

            // Date de création 
            $user->setCreatedAt(new DateTimeImmutable());

            // Hashage du mot de passe
            $plainPassword = $accountForm->get('plainPassword')->getData();
            $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Persistance en BDD avec l'entity manager
            $this->manager->persist($user);
            $this->manager->flush();
        
            // Message flash et redirection
            $this->addFlash('success', 'Votre compte est créé !');
            return $this->redirectToRoute('security_login');
        }

        // Affichage du formulaire de création de compte
        return $this->render('account/signup.html.twig', [
            'accountForm' => $accountForm->createView()
        ]);
    }
}
