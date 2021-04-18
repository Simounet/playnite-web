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
    <?php
        echo '<h1>Games number: ' . count($page->games) . '</h1>';
        array_map(function($game) use($page) {
            if($game['hidden'] === false) {
                echo '<div class="game-container">';
                if(is_string($game['cover-image'])) {
                    echo '<img src="' . $game['cover-image'] . '" alt="' . $game['name'] . '" height="200" />';
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
                echo '</div>';
            }
        }, $page->games);
    ?>
    <script type="text/javascript" src="script.js" async></script>
    <!-- Generated in <?php echo microtime(true) - $start;?> seconds -->
</body>
</html>
