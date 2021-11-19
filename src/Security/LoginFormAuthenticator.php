<?php 

namespace App\Security;

use App\Entity\User;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator {
    
    use TargetPathTrait;
    
    public const LOGIN_ROUTE = 'security_login';

    private $urlGenerator;
    private $formFactory;
    private $flashBag;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        FormFactoryInterface $formFactory,
        FlashBagInterface $flashBag
        )
    {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->flashBag = $flashBag;
    }

    public function getLoginUrl(Request $request): string 
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function authenticate(Request $request): PassportInterface
    {
        // 1. Récupérer les données du formulaire de connexion : email et mot de passe
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        $formData = $form->getData();

        $email = $formData['email'];
        $plainPassword = $formData['plainPassword'];

        // 2. Enregistrement en session de l'email pour le réafficher dans le formulaire en cas d'échec
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // 3. Création d'un passport pour l'utilisateur avec son email et son mot de passe
        $badge = new UserBadge($email);
        $credentials = new PasswordCredentials($plainPassword);

        return new Passport($badge, $credentials, [
            new CsrfTokenBadge('authenticate', $request->get('login')['_csrf_token'])
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // On récupère l'utilisateur connecté à partir du token

        /**
         * @var User
         */
        $user = $token->getUser();

        // Ajout d'un message flash de succès
        $this->flashBag->add('success', 'Bienvenue ' . $user->getFullName());

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirection vers la page d'accueil par défaut
        return new RedirectResponse($this->urlGenerator->generate('home_index'));
    }

}