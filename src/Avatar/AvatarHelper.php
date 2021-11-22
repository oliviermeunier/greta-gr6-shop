<?php

namespace App\Avatar;

use Symfony\Component\Filesystem\Filesystem;

class AvatarHelper {

    public const AVATAR_FOLDER = 'avatars';

    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {   
        $this->filesystem = $filesystem;
    }

    public function save(string $svg): string
    {
        $filename = sha1(uniqid(mt_rand(), true)) . '.svg';
        $filepath = self::AVATAR_FOLDER . '/' . $filename;

        $this->filesystem->mkdir(self::AVATAR_FOLDER);
        $this->filesystem->touch($filepath);
        $this->filesystem->appendToFile($filepath, $svg);

        return $filename;
    }
}