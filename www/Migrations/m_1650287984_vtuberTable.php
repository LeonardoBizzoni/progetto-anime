<?php

use app\core\Application;

class m_1650287984_vtuberTable
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "CREATE TABLE vtubers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                login VARCHAR(255) NOT NULL,
                img VARCHAR(512) NOT NULL,
                link VARCHAR(512) NOT NULL
              ) ENGINE=INNODB;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE vtubers";
        $db->pdo->exec($sql);
    }
}
?>
