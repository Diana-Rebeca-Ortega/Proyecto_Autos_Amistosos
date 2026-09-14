<?php
    class ConexionBDUsuarios {
        // 1. Propiedad Estática para almacenar la única instancia
        private static $instancia = null; 
        
        private $conexion;
        private $host = "localhost";
        private $puerto = "3306"; 
        private $usuario = "dianita";
        private $password = "dianita";
        private $bd = "BD_Usuarios_AutosAmistosos_2025";
        
        // 2. Constructor PRIVADO: Solo se puede llamar desde dentro de la clase
        private function __construct() {
            $dsn = "mysql:host={$this->host};port={$this->puerto};dbname={$this->bd}";

            try {
                $this->conexion = new PDO($dsn, $this->usuario, $this->password);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }  catch (PDOException $e) {
    // Esto detendrá la ejecución y te dirá exactamente qué pasó
    die("Fallo de conexión detallado: " . $e->getMessage());
}
        }
        
        // 3. Método Estático PÚBLICO: El punto de acceso global
        public static function getInstancia(): ConexionBDUsuarios {
            if (self::$instancia === null) {
                // Si no existe, crea la única instancia llamando al constructor privado
                self::$instancia = new self();
            }
            // Siempre devuelve la misma instancia existente
            return self::$instancia;
        }

        public function getConexion(): PDO {
            return $this->conexion;
        }

        // 4. Prohibir la clonación y deserialización (buenas prácticas del patrón)
        private function __clone() {}
        public function __wakeup() {}
    }
?>