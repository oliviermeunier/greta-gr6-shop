<?php

namespace App\Avatar;

use Symfony\Component\Filesystem\Filesystem;

class AvatarHelper {

    private $filesystem;
    private $avatarAbsolutePath;

    public function __construct(Filesystem $filesystem, string $avatarAbsolutePath)
    {   
        $this->avatarAbsolutePath = $avatarAbsolutePath;
        $this->filesystem = $filesystem;
    }

    public function save(string $svg): string
    {
        $filename = sha1(uniqid(mt_rand(), true)) . '.svg';
        $filepath = $this->avatarAbsolutePath . '/' . $filename;

        $this->filesystem->mkdir($this->avatarAbsolutePath);
        $this->filesystem->touch($filepath);
        $this->filesystem->appendToFile($filepath, $svg);

        return $filename;
    }

    public function removeAvatarFolder()
    {
        $this->filesystem->remove($this->avatarAbsolutePath);
    }
}