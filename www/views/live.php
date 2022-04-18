<?php
if (!isset($_GET["id"])) {
    echo "<h1>Live page</h1>";

    $form = app\core\forms\Form::begin("", "post");
    echo $form->field($model, "Link");
    echo '<button type="submit" class="btn btn-primary">Submit</button>';
    app\core\forms\Form::end();

    echo "<ul>";
    foreach ($params[0] as $idol) {
        echo "<li><a href='/live?id=" . $idol["id"] . "'>" . ucfirst($idol["username"]) . "</a></li>";
    }
    echo "</ul>";
} else {
    foreach ($params[0] as $vtuber) {
        if ($_GET["id"] == $vtuber["id"]) {
            echo "
<div id=\"parent\">
    <div>
      <nav class=\"navbar bg-dark text-white\">
          <div class=\"container-fluid\">
                  <h1>" . ucfirst($vtuber["username"]) . "</h1><img src='".$vtuber["img"]."'/>
          </div>
      </nav>
    </div>
</div>

<div id=\"child\">
<iframe src=\"https://player.twitch.tv/?channel=" . $vtuber["login"] . "&parent=localhost\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\"></iframe>
</div>
";
        }
    }
}
