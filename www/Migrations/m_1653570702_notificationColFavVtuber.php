<?php

use app\core\Application;

class m_1653570702_notificationColFavVtuber
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE favoriteVtuber ADD COLUMN notify BOOL DEFAULT 1;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE favoriteVtuber DROP COLUMN notify;";
        $db->pdo->exec($sql);
    }
}
?>
