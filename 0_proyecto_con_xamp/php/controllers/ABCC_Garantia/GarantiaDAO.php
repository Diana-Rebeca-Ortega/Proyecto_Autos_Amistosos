<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class GarantiaDAO {
    private $conexion; // Este será el objeto PDO

    public function __construct(){
        // CORRECCIÓN 1: USO CORRECTO DEL SINGLETON (Punto 8)
        // Llama a getInstancia() para obtener la única instancia y luego getConexion() para obtener el objeto PDO.
        $this->conexion = ConexionBDautosAmistosos::getInstancia()->getConexion();
    }

    public function obtenerTodasGarantias(): array {
        // CORRECCIÓN 2: Usar PDO directamente con $this->conexion y consultas preparadas.
        $sql = "SELECT idGarantia, Nombre_Garantia, Costo, Duracion_Meses 
                FROM garantia 
                ORDER BY idGarantia ASC";
        
        try {
            // Usamos prepare() incluso para SELECTs simples por consistencia y seguridad
            $stmt = $this->conexion->prepare($sql); 
            $stmt->execute();
            
            // PDO: Devolvemos un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error al obtener todas las garantías: " . $e->getMessage());
            return [];
        }
    }
}
?>