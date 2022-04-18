<?php

use app\core\Application;

class m_1650284365_addUsername
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE users ADD COLUMN username VARCHAR(255) NOT NULL;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE users DROP COLUMN username;";
        $db->pdo->exec($sql);
    }
}
?>
