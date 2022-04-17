<?php
namespace app\core\forms;
use app\core\BaseModel;

class Form {
    public static function begin(string $action, string $method) {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end() {
        echo "</form>";
    }

    public function field(BaseModel $model, string $attribute) {
        return new Field($model, $attribute);
    }
}
?>
