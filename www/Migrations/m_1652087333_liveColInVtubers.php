<?php

use app\core\Application;

class m_1652087333_liveColInVtubers
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE vtubers ADD COLUMN live VARCHAR(255) default NULL;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE vtubers DROP COLUMN live;";
        $db->pdo->exec($sql);
    }
}
?>
