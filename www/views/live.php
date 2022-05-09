<?php
if (isset($_GET["aggiungi"])) {
    $model->addToFav();
    exit;
}

if (!isset($_GET["id"])) {
    echo "<h1>Live page</h1>";

?>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add vtuber to the list</button>

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
    echo "<h2>Currently live</h2>";
    echo "<ul>";
    foreach ($params[0] as $idol) {
        if (count($idol[1]) > 0)
            echo "<li><a href='/live?id=" . $idol[0]["id"] . "'>" . ucfirst($idol[0]["username"]) . "</a></li>";
    }
    echo "</ul>";

    echo "<h2>Currently offline</h2>";
    echo "<ul>";
    foreach ($params[0] as $idol) {
        if (count($idol[1]) == 0)
            echo "<li><a href='/live?id=" . $idol[0]["id"] . "'>" . ucfirst($idol[0]["username"]) . "</a></li>";
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

            # TODO: controllo con session se l'utente è loggato
            // echo "<button id='add' class='btn btn-primary' name='add'>Add to favorites</button>";
            echo "<button id='add'>Add to favorites</button>";
            # -------------------------------------------------

            echo "<img class=\"idolLogo\" src=\"" . $vtuber[0]["img"] . "\"/>
        </div>
    </nav>

</div>

<div id=\"child\">";

            if (str_contains($vtuber[0]["link"], "twitch.tv")) {
                echo "<iframe src=\"https://player.twitch.tv/?channel=" . $vtuber[0]["login"] . "&parent=localhost\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\"></iframe>";
            } else {
                echo "<iframe src=\"https://www.youtube.com/embed/" . $vtuber[1][0] . "\" title=\"YouTube video player\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
            }
    ?>
            </div>
            </div>
            <script>
                document.getElementById('add').onclick = function() {
                    let req = new XMLHttpRequest();

                    req.open("get", "/live?aggiungi=1&id="+ <?php echo $_GET["id"] ?>, true);
                    req.send();
                };
            </script>
<?php
        }
    }
}
?>
