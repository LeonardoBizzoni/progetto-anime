<?php

use app\core\Application;

class m_1652080803_favoriteVtuber
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "CREATE TABLE favoriteVtuber (
                id INT AUTO_INCREMENT PRIMARY KEY,
                _vtuberID INT NOT NULL,
                _userID INT NOT NULL,

                FOREIGN KEY(_vtuberID) references `vtubers`(`id`),
                FOREIGN KEY(_userID) references `users`(`id`)
              ) ENGINE=INNODB;";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE favoriteVtuber;";
        $db->pdo->exec($sql);
    }
}
?>
