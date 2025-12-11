<?php
    class ConexionBDUsuarios{
        private $conexion;
        private $host = "localhost";
        private $puerto = "3306"; 
        private $usuario = "dianita";
        private $password = "dianita";
        private $bd = "BD_Usuarios_AutosAmistosos_2025";
        
        public function __construct(){
            // 1. Definición del DSN (Data Source Name)
            $dsn = "mysql:host={$this->host};port={$this->puerto};dbname={$this->bd}";

            try {
                // 2. Creación del objeto PDO
                $this->conexion = new PDO($dsn, $this->usuario, $this->password);
                
                //Configurar PDO para que lance excepciones de SQL (Buena práctica)
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                // 3. Manejo de errores correcto para PDO
                die("Error en la conexión a la BD de USUARIOS: " . $e->getMessage());
            }
        }

        public function getConexion(){
            return $this->conexion;
        }
    }
?>