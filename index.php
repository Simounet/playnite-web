<?php
use Simounet\PlayniteWeb\Page;

$start = microtime(true);
require_once(__DIR__ . '/classes/Page.php');
$page = new Page();
$viewMode = $_COOKIE['settings-view'] ?? 'grid';
$viewClass = $viewMode === 'grid' ? 'grid-view' : 'list-view';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Playnite games library</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="styles/awesomplete.css" />
    <link rel="stylesheet" href="styles/app.css" />
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
    <input id="games-input" class="awesomplete" list="games" />
    <datalist id="games">
      <?php
          foreach($page->alphabeticalList as $letter => $games) {
              foreach($games as $game) {
      ?>
      <option value="<?php echo $game['id']; ?>"><?php echo $game['name']; ?>
      <?php
              }
          }
      ?>
    </datalist>
    <div class="<?php echo $viewClass; ?>" data-toggle-id="view">
    <?php
        foreach($page->alphabeticalList as $letter => $games) {
            echo '<div class="grid-view-item games-list-letter-container"><h2 id="toc-' . $letter . '" class="games-list-letter">' . $letter . '</h2></div>';
            foreach($games as $game) {
                if($game['hidden'] === false) {
                    echo '<button id="' . $game['id'] . '" class="grid-view-item game-container">';
                    if(is_string($game['cover-image'])) {
                        echo '<img src="' . $game['cover-image'] . '" class="grid-view-item game-cover" alt="' . $game['name'] . '" height="200" />';
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
                class="view-button view-button--grid<?php echo $viewMode === 'grid' ? ' button-primary' : ''; ?>"
                data-toggle-view="grid"
            >
                Grid view
            </button>
            <button
                class="view-button view-button--list<?php echo $viewMode === 'list' ? ' button-primary' : ''; ?>"
                data-toggle-view="list"
            >
                List view
            </button>
        </div>
    </div>
    <script src="js/awesomplete.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
    <!-- Generated in <?php echo microtime(true) - $start;?> seconds -->
</body>
</html>
