#!/usr/bin/env bash

name="m_$(date +%s)_$2"
echo -e "<?php

use app\\\core\\\Application;

class $name
{
    public function up()
    {
        \$db = Application::\$app->db;
        \$sql = \"\";
        \$db->pdo->exec(\$sql);
    }

    public function down()
    {
        \$db = Application::\$app->db;
        \$sql = \"\";
        \$db->pdo->exec(\$sql);
    }
}
?>" > "$1/$name.php"
