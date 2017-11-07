<?php

namespace AppBundle\Helpers;

class FilesHelper
{
    protected $filesystem;
    protected $savePath;

    protected function generateDirName(): string
    {
        $t = md5(time());
        $t = substr($t, 0, 4);

        if (!$this->filesystem->exists($this->savePath . '/' . $t)) {
            $this->filesystem->mkdir($this->savePath . '/' . $t, 0777);
        }

        return $t;
    }

    protected function newFilename($ext)
    {
        return crc32(microtime(true)) . '.' . $ext;
    }
}
