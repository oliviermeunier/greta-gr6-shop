<?php 

namespace App\Controller;

use App\Avatar\AvatarSvgFactory;
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
    public function generateAvatar()
    {
        // Création d'un avatar
        $size = AvatarSvgFactory::DEFAULT_SIZE;
        $nbColors = AvatarSvgFactory::DEFAULT_NB_COLORS;
        $svg = $this->avatarSvgFactory->createRandomAvatar($size, $nbColors);

        // Construction de la réponse
        $response = new Response($svg);
        $response->headers->set('Content-Type', 'image/svg+xml');

        return $response;
    }
}