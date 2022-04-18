<?php
include_once "config.php";


$idolPage = $_GET["idolLink"];

if (str_contains($idolPage, "twitch.tv")) {
    $idol = str_replace("https://www.twitch.tv/", "", $idolPage);
    $url = "https://api.twitch.tv/helix/users?login=$idol";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [$token, $clientID]);

    $result = json_decode(curl_exec($ch));
    curl_close($ch);

    $result = get_object_vars($result);
    $result = get_object_vars($result["data"][0]);

    $idolFile = fopen("../vtuber/" . $idol . ".php", "w") or die("Unable to open file!");
    fwrite($idolFile, "<!doctype html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\"/>
    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3\" crossorigin=\"anonymous\">
    <link rel=\"stylesheet\" href=\"../assets/css/index.css\" type=\"text/css\" media=\"screen\" />
<title>" . $idol . "</title>
    </head>
<body>
    <div id=\"parent\">
    <div>
    <nav class=\"navbar bg-dark text-white\">
        <div class=\"container-fluid\">
                <h1>" . $idol . "</h1><img class=\"idolLogo\" src=\"" . $result["profile_image_url"] . "\"/>
        </div>
    </nav>

</div>

<div id=\"child\">
<iframe src=\"https://player.twitch.tv/?channel=".$result["login"]."&parent=localhost\" frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\"></iframe>
</div>
</div>

</body>
</html>
");
    fclose($idolFile);

    header("Location:/ProgettoAnime/vtuber.php");
} else if (str_contains($idolPage, "youtube.com")) {
    echo "Prima o poi";
} else {
    echo "wtf";
}
