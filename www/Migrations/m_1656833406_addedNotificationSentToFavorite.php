<?php

use app\core\Application;

class m_1656833406_addedNotificationSentToFavorite
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE vtubers ADD COLUMN sent BOOL default 0;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE vtubers DROP COLUMN sent;";
        $db->pdo->exec($sql);
    }
}
?>
