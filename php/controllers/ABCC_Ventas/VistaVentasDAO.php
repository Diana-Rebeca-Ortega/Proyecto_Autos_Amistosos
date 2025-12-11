<?php
// Asegúrate de que esta ruta sea correcta para incluir la conexión
include_once(__DIR__.'../../../database/conexion_bdd_autos_amistosos.php'); 

class VistaVentasDAO {
    private $conexion;

    public function __construct() {
        // USO CORRECTO DEL SINGLETON (PDO)
        $con = ConexionBDautosAmistosos::getInstancia();
        $this->conexion = $con->getConexion();
        // Si el Singleton lanza excepción, el código se detiene antes de aquí.
    }

    // Método 1: Listar todas las ventas (Estandarizado a PDO)
    public function listarVentas(): array {
        $sql = "SELECT * FROM VistaVenta ORDER BY Fecha_Venta DESC";
        
        try {
            // Usar prepare() incluso para SELECTs simples es buena práctica
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            
            // PDO: Obtener todos los resultados de una sola vez
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Manejo de errores de PDO
            error_log("Error al listar ventas desde VistaVenta: " . $e->getMessage());
            return [];
        }
    }

    // Método 2: Listar ventas de un vendedor específico (CORREGIDO A PDO)
    public function listarVentasPropias($idVendedor): array {
       
       $sql = "SELECT * FROM VistaVentaVendedor WHERE Vendedor_idVendedor = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            
            if ($stmt === false) {
                 error_log("Error preparando la consulta para listarVentasPropias.");
                 return [];
            }
        
            // CORRECCIÓN 1: Pasar el parámetro directamente a execute() o usar bindParam
            $stmt->execute([$idVendedor]); 
            
            // CORRECCIÓN 2 (Línea 53): En PDO, usamos fetchAll() directamente del statement
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
            
            // Nota: En PDO, no se usa $stmt->close() ni $stmt->get_result()
            
        } catch (PDOException $e) {
            error_log("Error al listar ventas propias: " . $e->getMessage());
            return [];
        }
    }

}
?>