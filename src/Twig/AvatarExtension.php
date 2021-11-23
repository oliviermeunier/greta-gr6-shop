<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AvatarExtension extends AbstractExtension
{
    private $avatarAbsoluteUrl;

    public function __construct(string $avatarAbsoluteUrl)
    {
        $this->avatarAbsoluteUrl = $avatarAbsoluteUrl;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_avatar', [$this, 'assetAvatar']),
        ];
    }

    public function assetAvatar($avatarFilename)
    {
        return $this->avatarAbsoluteUrl . '/' . $avatarFilename;
    }
}
