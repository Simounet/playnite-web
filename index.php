<?php
use Simounet\PlayniteWeb\Page;

$start = microtime(true);
require_once(__DIR__ . '/classes/Page.php');
$page = new Page();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Playnite games library</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="style.css" />
    <link rel="manifest" href="manifest.json">
    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />
</head>
<body>
    <h1 class="title">Games number: <?php echo count($page->games); ?></h1>
    <button class="settings-button" title="Settings" data-toggle-special="overlay" data-toggle-target="settings-container">
        <img src="assets/images/icons/settings.svg" alt="Settings" />
    </button>
    <ol class="toc-list">
        <?php
            array_map(function($letter) {
                echo '<li class="toc-list-item"><a href="#toc-' . $letter . '" class="toc-link">' . $letter . '</a></li>';
            }, array_keys($page->alphabeticalList));
        ?>
    </ol>
    <div class="grid-view" data-toggle-id="view">
    <?php
        foreach($page->alphabeticalList as $letter => $games) {
            echo '<h2 id="toc-' . $letter . '" class="games-list-letter">' . $letter . '</h2>';
            foreach($games as $game) {
                if($game['hidden'] === false) {
                    echo '<button class="game-container">';
                    if(is_string($game['cover-image'])) {
                        echo '<img src="' . $game['cover-image'] . '" class="game-cover" alt="' . $game['name'] . '" height="200" />';
                    }
                    echo '<span class="game-name">' . $game['name'] . '</span>';
                    echo '<div class="info" hidden>';
                    if($game['source-id']) {
                        echo 'Source: ' . $game['source-id'] . '<br />';
                    }
                    if($game['platform']) {
                        echo 'Platform: ' . $game['platform'] . '<br />';
                    }
                    if($game['playtime']) {
                        echo 'Playtime: ';
                        $page->playtimeDisplay($game['playtime']);
                        echo '<br />';
                    }
                    echo '</div>';
                    echo '</button>';
                }
            }
        }
    ?>
    </div>
    <div class="settings-container" data-toggle-id="settings-container" hidden>
        <button class="settings-button" title="Close" data-toggle-target="settings-container">
            <img src="assets/images/icons/close.svg" alt="Close" />
        </button>
        <div class="settings-item">
            <label class="setting-label" for="setting-cover-size">Cover size</label>
            <input
                type="range"
                id="setting-cover-size"
                class="cover-size-range"
                min="100"
                max="400"
                step="100"
                value="200"
            />
        </div>
        <div class="settings-item">
            <label class="setting-label" for="setting-game-name-displayed">Game name displayed</label>
            <input
                type="checkbox"
                id="setting-game-name-displayed"
                checked
            />
        </div>
        <div class="settings-item settings-view">
            <button
                class="view-button view-button--grid button-primary"
                data-toggle-view="grid"
            >
                Grid view
            </button>
            <button
                class="view-button view-button--list"
                data-toggle-view="list"
            >
                List view
            </button>
        </div>
    </div>
    <script type="text/javascript" src="script.js" async></script>
    <!-- Generated in <?php echo microtime(true) - $start;?> seconds -->
</body>
</html>
