<?php

use app\core\Application;

if (!Application::isGuest()) {
    $stmt = Application::$app->db->pdo->prepare("
SELECT vtubers.id, img, vtubers.username, notify
FROM users, vtubers, favoriteVtuber
WHERE users.id = _userID
AND vtubers.id = _vtuberID
AND users.id = ". Application::$app->user->id);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        if ($row["notify"] == 1) {
            $disable = "";
            $enable = "disabled";
        } else {
            $disable = "disabled";
            $enable = "";
        }

        echo "
<tr>
<td><img class='vtuber-img' src=\"{$row["img"]}\"/></td>
<td>{$row["username"]}</td>
<td>
    <form method='POST'>
        <button type='submit' name='rem' value='{$row["id"]}' class='btn btn-outline-danger'>Remove from favorites</button>
    </form>
</td>
<td>
    <form method='POST'>
        <button type='submit' name='notNotify' value='{$row["id"]}' class='btn btn-outline-danger' ". $disable .">Disable notifications</button>
    </form>
</td>
<td>
    <form method='POST'>
        <button type='submit' name='notify' value='{$row["id"]}' class='btn btn-outline-primary' $enable>Enable notifications</button>
    </form>
</td>
</tr>
";
    }

}
else {
    Application::$app->res->redirect("/");
}
?>
