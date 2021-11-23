<?php 

namespace App\Controller;

use App\Avatar\AvatarSvgFactory;
use App\Form\ReloadAvatarFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AjaxController extends AbstractController {

    private $avatarSvgFactory;

    public function __construct(AvatarSvgFactory $avatarSvgFactory)
    {
        $this->avatarSvgFactory = $avatarSvgFactory;
    }

    /**
     * @Route("/ajax/avatar/generate", name="ajax_avatar_generate")
     */
    public function generateAvatar(Request $request)
    {
        // On récupère la taille et le nombre de couleurs du formulaire
        $form = $this->createForm(ReloadAvatarFormType::class);
        $form->handleRequest($request);
        $data = $form->getData();

        // Création d'un avatar
        $size = $data['size'];
        $nbColors = $data['nb-colors'];
        $svg = $this->avatarSvgFactory->createRandomAvatar($size, $nbColors);

        // Construction de la réponse
        $response = new Response($svg);
        $response->headers->set('Content-Type', 'image/svg+xml');

        return $response;
    }
}