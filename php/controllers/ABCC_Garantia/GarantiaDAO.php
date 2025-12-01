<?php
include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class GarantiaDAO {
    private $conexion;
    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos (); 
    }

    public function obtenerTodasGarantias() {
        $conn = $this->conexion->getConexion(); 
        $sql = "SELECT idGarantia, Nombre_Garantia, Costo, Duracion_Meses FROM garantia ORDER BY idGarantia ASC";
        $resultado = $conn->query($sql);
        return $resultado; 
    }
  
}

?>