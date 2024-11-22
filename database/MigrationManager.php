<?php

class MigrationManager {
    private $pdo;
    private $migrationsPath;

    // Constructor que recibe la conexión PDO y la ruta de las migraciones
    public function __construct($pdo, $migrationsPath) {
        $this->pdo = $pdo;
        $this->migrationsPath = $migrationsPath;
    }

    // Ejecutar todas las migraciones pendientes
    public function migrate() {
        // Crear la tabla de migraciones si no existe
        $this->createMigrationTableIfNeeded();

        // Obtener migraciones aplicadas y disponibles
        $appliedMigrations = $this->getAppliedMigrations();
        $availableMigrations = $this->getAvailableMigrations();

        // Identificar migraciones pendientes
        $migrationsToApply = array_diff($availableMigrations, $appliedMigrations);

        if (empty($migrationsToApply)) {
            echo "No hay nuevas migraciones para aplicar.\n";
            return;
        }

        // Aplicar migraciones pendientes
        foreach ($migrationsToApply as $migration) {
            echo "Aplicando migración: $migration\n";
            $this->applyMigration($migration);
        }

        echo "Todas las migraciones han sido aplicadas.\n";
    }

    // Crear la tabla de migraciones si no existe
    private function createMigrationTableIfNeeded() {
        $query = "
        IF NOT EXISTS (
            SELECT * FROM sysobjects WHERE name = 'migration_versions' AND xtype = 'U'
        )
        CREATE TABLE migration_versions (
            version VARCHAR(255) PRIMARY KEY,
            applied_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($query);
    }

    // Obtener las migraciones que ya han sido aplicadas
    private function getAppliedMigrations() {
        $query = "SELECT version FROM migration_versions";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Obtener las migraciones disponibles en el directorio
    private function getAvailableMigrations() {
        $files = scandir($this->migrationsPath);
        return array_filter($files, fn($file) => preg_match('/\.php$/', $file));
    }

    // Aplicar una migración
    private function applyMigration($migration) {
        require_once $this->migrationsPath . '/' . $migration;

        // Verificar que la función 'up' existe
        if (!function_exists('up')) {
            echo "Error: La función 'up' no está definida en la migración: $migration\n";
            return;
        }

        try {
            // Iniciar una transacción
            $this->pdo->beginTransaction();

            // Ejecutar la migración
            up($this->pdo);

            // Registrar la migración
            $this->recordMigration($migration);

            // Confirmar la transacción
            $this->pdo->commit();

            echo "Migración aplicada exitosamente: $migration\n";
        } catch (Exception $e) {
            // Revertir cambios si ocurre un error
            $this->pdo->rollBack();
            echo "Error al aplicar migración $migration: " . $e->getMessage() . "\n";
        }
    }

    // Registrar la migración como aplicada
    private function recordMigration($migration) {
        $query = "INSERT INTO migration_versions (version) VALUES (:version)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':version', $migration);
        $stmt->execute();
    }
}

