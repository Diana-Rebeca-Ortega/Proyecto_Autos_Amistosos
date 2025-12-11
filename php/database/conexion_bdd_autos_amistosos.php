<?php

class ConexionBDautosAmistosos {
    private $conexion;
    private $host = "localhost";
    private $puerto = "3306";
    private $usuario = "dianita";
    private $password = "dianita";
    private $bd = "autos_amistosos";
    public function __construct() {       
        $dsn = "mysql:host={$this->host};port={$this->puerto};dbname={$this->bd}";
        try {
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            
            // Configurar PDO para que lance excepciones en caso de error
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Error en la conexión a la BD: " . $e->getMessage());
        }
    }

    public function getConexion() {
        return $this->conexion;
    }
}
?>