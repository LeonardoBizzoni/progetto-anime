<?php

namespace app\core;

abstract class DbModel extends BaseModel
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr",  $attributes);

        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") VALUES (".implode(',', $params).")");

        foreach ($attributes as $attr) {
            $statement->bindValue(":$attr", $this->{$attr});
        }

        $statement->execute();
        return true;
    }

    public static function prepare(string $sql) {
        return  Application::$app->db->pdo->prepare($sql);
    }
}
