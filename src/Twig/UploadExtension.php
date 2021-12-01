<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UploadExtension extends AbstractExtension
{
    private $uploadsAbsoluteUrl;

    public function __construct(string $uploadsAbsoluteUrl)
    {
        $this->uploadsAbsoluteUrl = $uploadsAbsoluteUrl;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_upload', [$this, 'assetUpload']),
        ];
    }

    public function assetUpload($uploadFilename)
    {
        // Si c'est une URL on la retourne telle quelle
        if (filter_var($uploadFilename, FILTER_VALIDATE_URL)) {
            return $uploadFilename;
        }

        return $this->uploadsAbsoluteUrl . '/' . $uploadFilename;
    }
}
