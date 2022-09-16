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

    private const GAMES_PATH = self::DATA_DIR . '/games.json';

    private const FILES_WEB_PATH = self::LIBRARY_DIR . '/files/';

    public $alphabeticalList = [];
    public $games = [];

    private $pic;

    public function __construct() {
        $this->structureSetup();
        require_once(__DIR__ . '/Pic.php');
        $this->pic = new Pic(self::ASSETS_PATH);
        $this->games = $this->getGames();
        $this->alphabeticalList = $this->alphabeticalList($this->games);
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

    private function removeUtf8Bom(string $str): string {
        return substr($str, 3);
    }

    private function getGames(): array {
        $gamesInfo = file_get_contents(self::GAMES_PATH);
        $json = json_decode($this->removeUtf8Bom($gamesInfo));
        $games = array_map(function(object $game): array {
            return [
                'id' => $game->Id,
                'name' => $game->Name,
                'cover-image' => $this->coverImage($game),
                'playtime' => $game->Playtime ?? 0,
                'last-activity' => $game->LastActivity ?? 0,
                'source-id' => isset($game->Source) && isset($game->Source->Name) ? $game->Source->Name : false,
                'platform' => isset($game->Platforms) ? $game->Platforms[0] : '',
                'hidden' => isset($game->Hidden) ? (bool) $game->Hidden : false
            ];
        }, $json);

        usort($games, function(array $a, array $b) {
            //return $b['playtime] - $a['playtime];
            return $b['name'] < $a['name'];
        });
        return $games;
    }

    private function alphabeticalList($games) {
        $alphabetical = [];
        foreach($games as $game) {
            if($game['hidden'] === false) {
                $name = $game['name'];
                $firstLetter = strtoupper(mb_substr($name, 0, 1, "UTF-8"));
                $alphabetical[$firstLetter][] = $game;
            }
        }
        return $alphabetical;
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
