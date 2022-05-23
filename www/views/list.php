<?php

use app\core\Application;

if (!Application::isGuest()) :
?>


<?php
else:
    Application::$app->res->redirect("/");

endif; ?>
