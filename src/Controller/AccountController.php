<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Avatar\AvatarHelper;
use App\Avatar\AvatarSvgFactory;
use App\Form\ReloadAvatarFormType;
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
    private $avatarSvgFactory;
    private $avatarHelper;

    public function __construct(
        UserPasswordHasherInterface $hasher, 
        EntityManagerInterface $manager,
        AvatarSvgFactory $avatarSvgFactory,
        AvatarHelper $avatarHelper)
    {
        $this->hasher = $hasher;
        $this->manager = $manager;
        $this->avatarSvgFactory = $avatarSvgFactory;
        $this->avatarHelper = $avatarHelper;
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

            // Avatar
            $svg = $request->request->get('svg');
            $filename = $this->avatarHelper->save($svg);
            $user->setAvatar($filename);

            // Persistance en BDD avec l'entity manager
            $this->manager->persist($user);
            $this->manager->flush();
        
            // Message flash et redirection
            $this->addFlash('success', 'Votre compte est créé !');
            return $this->redirectToRoute('security_login');
        }

        // Création d'un avatar
        $size = AvatarSvgFactory::DEFAULT_SIZE;
        $nbColors = AvatarSvgFactory::DEFAULT_NB_COLORS;
        $svg = $this->avatarSvgFactory->createRandomAvatar($size, $nbColors);

        // Formulaire de rechargement de l'avatar
        $generateForm = $this->createForm(ReloadAvatarFormType::class, [
            'size' => $size,
            'nb-colors' => $nbColors
        ], [
            'action' => $this->generateUrl('ajax_avatar_generate')
        ]);

        // Affichage du formulaire de création de compte
        return $this->render('account/signup.html.twig', [
            'accountForm' => $accountForm->createView(),
            'svg' => $svg,
            'generateForm' => $generateForm->createView()
        ]);
    }
}
