<?php

use app\core\Application;
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <title><?= $this->title ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/img/XtPIXR8.png" alt="" width="80" height="auto">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav" style="display: flex;">

                <?php if (Application::isGuest()) : ?>
                    <ul class="navbar-nav" style="margin-right: 0px; margin-left: auto;">
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Register</a>
                        </li>
                    </ul>
                <?php else : ?>
                    <ul class="navbar-nav" style="margin-right: 0px; margin-left: auto;">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <?php if (!Application::isGuest()) {
                                echo "<li class='nav-item btn btn-outline-primary'>";
                                echo "<a class='nav-link' data-bs-toggle='modal' data-bs-target='#exampleModal' href=''>Add vtuber to the list</a>";
                                echo "</li>";
                            }
                            ?>
                            <li class="nav-item btn btn-outline-primary">
                                <a class="nav-link" href="/list">Favorites</a>
                            </li>
                            <li class="nav-item btn btn-outline-primary">
                                <a class="nav-link" href="/logout">Logout</a>
                            </li>
                        </div>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        {{content}}
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>

</html>
