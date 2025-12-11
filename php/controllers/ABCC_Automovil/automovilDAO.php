<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class AutomovilDAO {
    private $conexion; // Este será el objeto PDO

    public function __construct(){
        // CORRECCIÓN 1: USO CORRECTO DEL SINGLETON (Punto 8)
        // Llama al método estático getInstancia() para obtener la única instancia
        $con = ConexionBDautosAmistosos::getInstancia();
        // Luego obtiene la conexión PDO de esa instancia
        $this->conexion = $con->getConexion();
    }

    public function obtenerTodos(): array {
        // CORRECCIÓN 2: Usar PDO directamente con $this->conexion
        $sql = "SELECT idAutomovil, Modelo, Precio_Lista, FechaFabricacion, Color, Kilometraje_Entrega, Estado, Tipo_Vehiculo FROM automovil";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            // PDO: Devolvemos un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener todos los automóviles: " . $e->getMessage());
            return [];
        }
    }
public function obtenerDisponibles(): array {
    // Solo selecciona los campos que necesitas para el dropdown y filtra por 'DISPONIBLE'
    $sql = "SELECT idAutomovil, Modelo, Tipo_Carroceria, Precio_Lista, Kilometraje_Entrega 
            FROM Automovil 
            WHERE Estado = 'DISPONIBLE'"; 
    try {
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        // Devuelve un array de arrays asociativos: [{'idAutomovil': 'DEF-987...', 'Modelo': 'Civic', ...}, ...]
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    } catch (PDOException $e) {
        error_log("Error al obtener Automóviles disponibles: " . $e->getMessage());
        return []; // Retorna un array vacío en caso de error
    }
}
    // Aquí puedes añadir más métodos como obtenerPorId, etc.
}
?>