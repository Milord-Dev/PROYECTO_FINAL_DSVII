<?php

class Database {
    // Instancia única de la clase (Patrón Singleton)
    private static $instance = null;
    
    // Conexión PDO
    private $conn;
    
    // Propiedades de configuración (para permitir diferentes configuraciones)
    private $host = 'localhost'; // o el host adecuado
    private $dbname = 'AgenciaViajes';
    private $username = 'root';  // o el nombre de usuario adecuado
    private $password = '';      // o la contraseña adecuada
    private $charset = 'utf8mb4';
    
    // Constructor privado para prevenir la creación directa de objetos
    private function __construct() {
        // Configuración de la base de datos
        try {
            // Creamos la conexión PDO con un manejo de errores adecuado
            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // Manejo de error si la conexión falla
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Método para obtener la instancia única de la clase
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Método para obtener la conexión PDO
    public function getConnection() {
        return $this->conn;
    }

    // Método para desconectar la base de datos
    public function disconnect() {
        $this->conn = null;
    }

    // Método para cambiar la base de datos sin tener que crear una nueva instancia
    public function setDatabase($dbname) {
        $this->dbname = $dbname;
        $this->conn->exec("USE $dbname");
    }

    // Método para obtener la base de datos actual
    public function getDatabase() {
        return $this->dbname;
    }
}
?>
