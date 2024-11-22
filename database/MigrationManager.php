<?php

class MigrationManager {
    private $pdo;
    private $migrationsPath;

    public function __construct($pdo, $migrationsPath) {
        $this->pdo = $pdo;
        $this->migrationsPath = $migrationsPath;
    }

    public function migrate() {
        $this->createMigrationsTableIfNotExists();

        $appliedMigrations = $this->getAppliedMigrations();
        $migrationFiles = $this->getMigrationFiles();
        $newMigrations = array_diff($migrationFiles, $appliedMigrations);

        foreach ($newMigrations as $migration) {
            try {
                $this->pdo->beginTransaction();
                $this->applyMigration($migration);
                $this->pdo->commit();
            } catch (Exception $e) {
                $this->pdo->rollBack();
                echo "Error applying migration '$migration': " . $e->getMessage() . "\n";
                exit;
            }
        }

        if (empty($newMigrations)) {
            echo "No new migrations to apply.\n";
        } else {
            echo "All migrations applied successfully.\n";
        }
    }

    private function createMigrationsTableIfNotExists() {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration NVARCHAR(255) NOT NULL,
            applied_at DATETIME DEFAULT GETDATE()
        )");
    }

    private function getAppliedMigrations() {
        $statement = $this->pdo->query("SELECT migration FROM migrations");
        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getMigrationFiles() {
        $files = scandir($this->migrationsPath);
        $migrations = array_filter($files, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
        });
        sort($migrations);
        return $migrations;
    }

    private function applyMigration($migration) {
        $sql = file_get_contents($this->migrationsPath . '/' . $migration);
        $this->pdo->exec($sql);
        $this->logMigration($migration);
        echo "Applied migration: $migration\n";
    }

    private function logMigration($migration) {
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
        $statement->execute(['migration' => $migration]);
    }
}
