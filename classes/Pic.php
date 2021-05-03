<?php

namespace Simounet\PlayniteWeb;

class Pic {

    private const FOLDER_NAME = 'pics';

    private $format = 'webp';
    private $picsPath = '';

    public function __construct($path) {
        $this->picsPath = $path . '/' . self::FOLDER_NAME . '/';
        $this->selectFormat();
    }

    public function getPath($pic) {
        $pathInfo = pathinfo($pic);
        $generatedPicPath = $this->picsPath . $pathInfo['filename'] . '.' . $this->format;
        $generatedPic = $this->generate($generatedPicPath, $pic);
        return $generatedPic;
    }

    private function generate($generatedPic, $pic) {
        if(!file_exists($generatedPic)) {
            $imagick = new \Imagick($pic);
            $imagick->resizeImage(300, 400, \Imagick::FILTER_LANCZOS, 1);
            $imagick->setImageFormat($this->format);
            $imagick->writeImage($generatedPic);
        }
        return file_exists($generatedPic) ? $generatedPic : false;
    }

    public function size(string $file): string {
        $path = $this->src($file);
        $info = getimagesize(__DIR__ . '/../' . $path);
        return $info[0] . 'x' . $info[1];
    }

    private function selectFormat() {
        $input = \Imagick::queryformats();
        if(!in_array(strtoupper($this->format), $input)) {
            $this->format = 'jpg';
        }
    }
}
