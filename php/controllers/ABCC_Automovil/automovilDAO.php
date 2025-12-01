<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class AutomovilDAO {
    private $conexion;
    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos ();
    }

    public function obtenerTodos() {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idAutomovil, Modelo, Precio_Lista, FechaFabricacion, Color, Kilometraje_Entrega, Estado, Tipo_Vehiculo FROM automovil";
        $res = $conn->query($sql);
        return $res;
        }

        public function obtenerDisponibles() {
        $conn = $this->conexion->getConexion();
       $sql = "SELECT 
            idAutomovil, 
            Modelo, 
            Precio_Lista, 
            Tipo_Carroceria,  
            Color, 
            Condicion,
            Kilometraje_Entrega
        FROM 
            automovil
        WHERE 
            Estado = 'DISPONIBLE' OR Estado = 'NUEVO'"; 
        $res = $conn->query($sql);
        return $res;
        }
    }
?>