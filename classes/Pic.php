<?php

namespace Simounet\PlayniteWeb;

class Pic {

    private const FOLDER_NAME = 'pics';

    private $picsPath = '';

    public function __construct($path) {
        $this->picsPath = $path . '/' . self::FOLDER_NAME . '/';
    }

    public function getPath($pic) {
        $pathInfo = pathinfo($pic);
        $generatedPicPath = $this->picsPath . $pathInfo['filename'] . '.webp';
        $generatedPic = $this->generate($generatedPicPath, $pic);
        return $generatedPic;
    }

    private function generate($generatedPic, $pic) {
        if(!file_exists($generatedPic)) {
            $command = 'cwebp "' . $pic . '" -resize 300 400 -o "' . $generatedPic . '"';
            // @DEBUG
            // echo $command . PHP_EOL;
            exec($command);
        }
        return file_exists($generatedPic) ? $generatedPic : false;
    }

    public function size(string $file): string {
        $path = $this->src($file);
        $info = getimagesize(__DIR__ . '/../' . $path);
        return $info[0] . 'x' . $info[1];
    }
}
