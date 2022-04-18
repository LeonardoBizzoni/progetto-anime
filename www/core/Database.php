<?php

namespace app\core;

use PDO;

class Database
{
    public PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config["dsn"] ?? "";
        $user = $config["user"] ?? "";
        $password = $config["password"] ?? "";

        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $applied = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . "/Migrations");
        $toApply = array_diff($files, $applied);

        foreach ($toApply as $migration) {
            if ($migration == "." || $migration == "..") {
                continue;
            }

            require_once Application::$ROOT_DIR . "/Migrations/$migration";
            $migrationClassName = pathinfo($migration, PATHINFO_FILENAME);

            $this->log("Applying migration: $migration");
            $migrationInstance = new $migrationClassName;
            $migrationInstance->up();
            $this->log("Applied migration: $migration");

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigration($newMigrations);
        } else {
            $this->log("All migrations are applied!");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("
CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;
");
    }

    public function getAppliedMigrations()
    {
        $statment = $this->pdo->prepare("SELECT migration FROM migrations");
        $statment->execute();

        return $statment->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigration(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $this->pdo->prepare("INSERT INTO migrations(migration) VALUES $str")->execute();
    }

    protected function log(string $message) {
        echo "[".date("Y/m/d H:m:s")."] - $message".PHP_EOL;
    }
}
