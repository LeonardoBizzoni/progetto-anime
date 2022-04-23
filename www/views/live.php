<?php
if (!isset($_GET["id"])) {
    echo "<h1>Live page</h1>";

    $form = app\core\forms\Form::begin("", "post");
    echo $form->field($model, "Link");
    echo '<button type="submit" class="btn btn-primary">Submit</button>';
    app\core\forms\Form::end();

    echo "<h2>Currently live</h2>";
    echo "<ul>";
    foreach ($params[0] as $idol) {
        if (count($idol["vtuber"][1]) > 0)
            echo "<li><a href='/live?id=" . $idol["vtuber"][0]["id"] . "'>" . ucfirst($idol["vtuber"][0]["username"]) . "</a></li>";
    }
    echo "</ul>";

    echo "<h2>Currently offline</h2>";
    echo "<ul>";
    foreach ($params[0] as $idol) {
        if (count($idol["vtuber"][1]) == 0)
            echo "<li><a href='/live?id=" . $idol["vtuber"][0]["id"] . "'>" . ucfirst($idol["vtuber"][0]["username"]) . "</a></li>";
    }
    echo "</ul>";
} else {
    foreach ($params[0] as $vtuber) {
        if ($_GET["id"] == $vtuber["vtuber"][0]["id"]) {
            echo "
    <div id=\"parent\">
    <div>
    <nav class=\"navbar bg-dark text-white\">
        <div class=\"container-fluid\">
                <h1>" . ucfirst($vtuber["vtuber"][0]["username"]) . "</h1><img class=\"idolLogo\" src=\"" . $vtuber["vtuber"][0]["img"] . "\"/>
        </div>
    </nav>

</div>

<div id=\"child\">";

            if (str_contains($vtuber["vtuber"][0]["link"], "twitch.tv")) {
                echo "<iframe src=\"https://player.twitch.tv/?channel=".$vtuber["vtuber"][0]["login"]."&parent=localhost\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\"></iframe>";
            } else {
                echo "<iframe src=\"https://www.youtube.com/embed/".$vtuber["vtuber"][1][0]."\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
            }

            echo "</div></div>";
        }
    }
}
