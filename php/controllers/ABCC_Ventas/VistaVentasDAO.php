<?php
// Asegúrate de que esta ruta sea correcta para incluir la conexión
include_once(__DIR__.'../../../database/conexion_bdd_autos_amistosos.php'); 

class VistaVentasDAO {
    private $conexion;

    public function __construct() {
        // Inicializa la conexión a la base de datos principal
        $con = new ConexionBDautosAmistosos();
        $this->conexion = $con->getConexion();

        if (!$this->conexion) {
            // Manejo de error si la conexión falla al inicializar el DAO
            error_log("Error: La conexión a la BD de autos falló en VentasDAO.");
        }
    }

    /**
     * Obtiene el listado completo de ventas consultando la VIEW VistaVenta.
     * @return array|false Un array de filas o false si hay un error o no hay resultados.
     */
    public function listarVentas() {
        // La consulta simplemente selecciona todo de la VIEW.
        $sql = "SELECT * FROM VistaVenta ORDER BY Fecha_Venta DESC";
        
        $resultado = $this->conexion->query($sql);

        if ($resultado) {
            if ($resultado->num_rows > 0) {
                $ventas = [];
                while ($fila = $resultado->fetch_assoc()) {
                    $ventas[] = $fila;
                }
                $resultado->free();
                return $ventas;
            } else {
                // No hay resultados
                return [];
            }
        } else {
            // Error en la ejecución de la consulta
            error_log("Error al listar ventas desde VistaVenta: " . $this->conexion->error);
            return false;
        }
    }

    public function cerrarConexion() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>