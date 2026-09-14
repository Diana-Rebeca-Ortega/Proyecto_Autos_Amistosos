<?php

require_once(__DIR__.'../../../database/conexion_bdd_autos_amistosos.php'); 

class ReporteDAO {
    private $conexion;

    // Constructor que establece la conexión PDO usando el patrón Singleton
    public function __construct() {
        // 1. Obtener la instancia ÚNICA de la clase de conexión
        $instancia_singleton = ConexionBDautosAmistosos::getInstancia();
        
        if ($instancia_singleton === null) {
            die("Error Fatal: La instancia SINGLETON de conexión es NULA en ReporteDAO.");
        }
        
        // 2. Obtener el objeto PDO de la instancia Singleton
        $this->conexion = $instancia_singleton->getConexion();

        if (!$this->conexion) {
            die("Error Fatal: No se pudo obtener la conexión PDO en ReporteDAO.");
        }
    }

  public function obtenerReporteDesempenoVendedor($fechaInicio, $fechaFin) {
    $sql = "CALL GenerarReporteDesempenoVendedor(?, ?)";
    
    try {
        $stmt = $this->conexion->prepare($sql);
        
        // 1. Vinculación de parámetros
        $stmt->bindParam(1, $fechaInicio);
        $stmt->bindParam(2, $fechaFin);
        
        // 2. Ejecución
        if (!$stmt->execute()) {
            // Si la ejecución falla, registramos el error de PDO
            error_log("Fallo en EXECUTE del reporte de vendedor: " . json_encode($stmt->errorInfo()));
            return [];
        }
        
        // 3. Recuperación de resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 4. Cierre del cursor (CRUCIAL para procedimientos almacenados)
        $stmt->closeCursor(); 
        
        return $resultados;
        
    } catch (PDOException $e) {
        // Manejo de errores de conexión/BD
        error_log("Excepción al obtener reporte de vendedor: " . $e->getMessage());
        return [];
    }
}

    public function obtenerDetalleVentaConImpuesto($idVenta) {
       
    }
}
?>