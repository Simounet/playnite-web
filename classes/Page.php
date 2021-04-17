<?php

namespace Simounet\PlayniteWeb;

use Simounet\PlayniteWeb\Pic;

class Page {

    private const ASSETS_DIR = 'assets';
    private const ASSETS_PATH = __DIR__ . '/../' . self::ASSETS_DIR;
    private const DATA_DIR = 'data';
    private const DATA_PATH = __DIR__ . '/../' . self::DATA_DIR;
    private const LIBRARY_DIR = self::DATA_DIR . '/library';
    private const LIBRARY_PATH = __DIR__ . '/../' . self::LIBRARY_DIR;

    private const GAMES_PATH = self::LIBRARY_PATH . '/games/';
    private const PLATFORMS_PATH = self::LIBRARY_PATH . '/platforms/';
    private const SOURCES_PATH = self::LIBRARY_PATH . '/sources/';

    private const FILES_WEB_PATH = self::LIBRARY_DIR . '/files/';

    public $games = [];
    public $platforms = [];
    public $sources = [];

    private $pic;

    public function __construct() {
        $this->structureSetup();
        require_once(__DIR__ . '/Pic.php');
        $this->pic = new Pic(self::ASSETS_PATH);
        $this->sources = $this->getContentFromFiles(self::SOURCES_PATH);
        $this->platforms = $this->getContentFromFiles(self::PLATFORMS_PATH);
        $this->games = $this->getGames($this->platforms, $this->sources);
    }

    private function structureSetup() {
        array_map(function($path) {
            if(!file_exists($path)) {
                // @DEBUG
                // echo $folder . PHP_EOL;
                mkdir($path, 0777, true);
            }
        }, [self::ASSETS_PATH, self::DATA_PATH]);
    }

    private function getGames(array $platforms, array $sources): array {
        $files = $this->getFiles(self::GAMES_PATH);
        $games = array_map(function(string $fileName) use($platforms, $sources): array {
            $content = file_get_contents(self::GAMES_PATH . $fileName);
            $json = json_decode($content);
            return [
                'id' => $json->Id,
                'name' => $json->Name,
                'cover-image' => $this->coverImage($json),
                'playtime' => $json->Playtime ?? 0,
                'last-activity' => $json->LastActivity ?? 0,
                'source-id' => isset($json->SourceId) ? $sources[$json->SourceId] : false,
                'platform' => $platforms[$json->PlatformId],
                'hidden' => isset($json->Hidden) ? (bool) $json->Hidden : false
            ];
        }, $files);

        usort($games, function(array $a, array $b) {
            //return $b['playtime] - $a['playtime];
            return $b['name'] < $a['name'];
        });
        return $games;
    }

    private function coverImage($json)
    {
        $imagePath = isset($json->CoverImage) ? self::FILES_WEB_PATH . str_replace('\\', '/', $json->CoverImage) : false;
        $baseDir = __DIR__ . '/../';
        return $imagePath ? str_replace($baseDir, '', $this->pic->getPath($baseDir . $imagePath)) : false;
    }

    private function getContentFromFiles(string $dir): array {
        $files = $this->getFiles($dir);
        $content = [];
        foreach( $files as $fileName ) {
            $fileContent = file_get_contents($dir . $fileName);
            $json = json_decode($fileContent);
            $content[$json->Id] = $json->Name;
        }
        return $content;
    }

    protected function getFiles(string $dir): array {
        return array_filter(scandir($dir), function($name) {
            return strpos($name, '.json');
        });
    }

    public function playtimeDisplay(string $time): bool {
        $minutes = (int) $time / 60;
        if($minutes < 60) {
            echo round($minutes, 1) . 'mn';
        } else {
            echo round($minutes / 60, 1) . 'h';
        }
        return true;
    }
}
