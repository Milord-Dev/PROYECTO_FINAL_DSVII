<?php

class Database {
    // Instancia única de la clase (Patrón Singleton)
    private static $instance = null;
    
    // Conexión PDO
    private $conn;
    
    // Propiedades de configuración (para permitir diferentes configuraciones)
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;

    // Constructor privado para prevenir la creación directa de objetos
    private function __construct() {
        // Cargar configuración desde variables de entorno (recomendado para producción)
        $this->host = getenv('DB_HOST') ?: 'localhost'; // 'localhost' como valor por defecto
        $this->dbname = getenv('DB_NAME') ?: 'AgenciaViajes';
        $this->username = getenv('DB_USER') ?: 'root';  // 'root' como valor por defecto
        $this->password = getenv('DB_PASS') ?: '';      // Contraseña vacía por defecto
        $this->charset = 'admin123';                      // Charset para evitar problemas con caracteres especiales

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

    // Método para obtener la instancia única de la clase (Singleton)
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

    // Método para desconectar la base de datos de forma segura
    public function disconnect() {
        $this->conn = null;
    }

    // Método para cambiar la base de datos sin tener que crear una nueva instancia
    public function setDatabase($dbname) {
        try {
            $this->dbname = $dbname;
            // Cambiar la base de datos utilizando el comando SQL 'USE'
            $this->conn->exec("USE $dbname");
        } catch (PDOException $e) {
            die("Failed to switch database: " . $e->getMessage());
        }
    }

    // Método para obtener la base de datos actual
    public function getDatabase() {
        return $this->dbname;
    }

    // Método para cambiar las credenciales de conexión
    public function setCredentials($username, $password) {
        $this->username = $username;
        $this->password = $password;

        try {
            $this->conn = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=$this->charset",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Failed to update credentials: " . $e->getMessage());
        }
    }
}

?>
