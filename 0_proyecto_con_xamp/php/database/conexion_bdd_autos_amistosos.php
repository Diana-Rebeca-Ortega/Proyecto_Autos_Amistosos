<?php

class ConexionBDautosAmistosos {
    // 1. Propiedad Estática para la única instancia (Singleton)
    private static $instancia = null; 
    
    private $conexion;
    private $host = "localhost";
    private $puerto = "3306";
    private $usuario = "dianita";
    private $password = "dianita";
    private $bd = "autos_amistosos"; 
    
    // 2. Constructor PRIVADO: Solo accesible desde dentro de la clase
    private function __construct() { 
        $dsn = "mysql:host={$this->host};port={$this->puerto};dbname={$this->bd}";

        try {
            // Creación del objeto PDO
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            
            // Configurar PDO para lanzar excepciones
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            // Lanza una excepción para que el código llamador la maneje
            throw new Exception("Error en la conexión a la BD de Autos: " . $e->getMessage());
        }
    }

    // 3. Método Estático PÚBLICO: El punto de acceso global (Singleton)
    public static function getInstancia(): ConexionBDautosAmistosos {
        if (self::$instancia === null) {
            // Si no existe, crea la instancia única
            self::$instancia = new self();
        }
        // Devuelve la única instancia
        return self::$instancia;
    }

    public function getConexion(): PDO {
        return $this->conexion;
    }

    // Prohibir la clonación y deserialización
    private function __clone() {}
    public function __wakeup() {}
}

?>