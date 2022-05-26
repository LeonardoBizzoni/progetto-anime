<?php

use app\core\Application;

if (isset($_GET["aggiungi"])) {
    $model->addToFav();
    exit;
}

if (count($model->errors) > 0) {
    echo "<pre>";
    var_dump($model->errors);
    echo "</pre>";
} else if (!isset($_GET["id"])) {
    echo "<h1>Live page</h1>";
?>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add vtuber to the list</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $form = app\core\forms\Form::begin("", "post");
                    echo $form->field($model, "Link");
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <?php app\core\forms\Form::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (!Application::isGuest()) {
        echo "<h2>Favorites</h2>";
        echo "<ul class='list-group list-group-flush'>";
        foreach ($params[0] as $idol) {
            if (gettype($idol[1]) == "string" && in_array($idol[0]["id"], $params[1])) {
                echo "<li class='list-group-item'><span class='badge bg-danger'>Live</span> <a href='/?id=" . $idol[0]["id"] . "'>" . ucfirst($idol[0]["username"]) . "</a></li>";
            }
        }
        echo "</ul>";
    }

    echo "<h2>Currently live</h2>";
    echo "<ul class='list-group list-group-flush'>";
    foreach ($params[0] as $idol) {
        if (gettype($idol[1]) == "string") {
            echo "<li class='list-group-item'><span class='badge bg-danger'>Live</span> <a href='/?id=" . $idol[0]["id"] . "'>" . ucfirst($idol[0]["username"]) . "</a></li>";
        }
    }
    echo "</ul>";

    echo "<h2>Currently offline</h2>";
    echo "<ul>";
    foreach ($params[0] as $idol) {
        if (gettype($idol[1]) == "array")
            echo "<li><a href='/?id=" . $idol[0]["id"] . "'>" . ucfirst($idol[0]["username"]) . "</a></li>";
    }
    echo "</ul>";
} else {
    foreach ($params[0] as $vtuber) {
        if ($_GET["id"] == $vtuber[0]["id"]) {
            echo "
    <div id=\"parent\">
    <div>
    <nav class=\"navbar bg-dark text-white\">
        <div class=\"container-fluid\">
                <h1>" . ucfirst($vtuber[0]["username"]) . "</h1>";

            if (!Application::isGuest() && !in_array($vtuber[0]["id"], $params[1])) {
                echo "<button id='add' type='button' class='btn btn-outline-light'>Add to favorites</button>";
            }

            echo "<img class=\"idolLogo\" src=\"" . $vtuber[0]["img"] . "\"/>
        </div>
    </nav>

</div>

<div id=\"child\">";

            if (str_contains($vtuber[0]["link"], "twitch.tv")) {
                echo "<iframe src=\"https://player.twitch.tv/?channel=" . $vtuber[0]["login"] . "&parent=localhost\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\"></iframe>";
            } else {
                echo "<iframe src=\"" . str_replace("watch?v=", "embed/", $vtuber[1]) . "\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
            }
    ?>
            </div>
            </div>
            <script>
                let btn = document.getElementById('add');
                btn.onclick = function() {
                    let req = new XMLHttpRequest();

                    req.open("get", "/?aggiungi=1&id=" + <?php echo $_GET["id"] ?>, true);
                    req.send();

                    btn.style = "display: none";
                };
            </script>
<?php
        }
    }
}
?>
