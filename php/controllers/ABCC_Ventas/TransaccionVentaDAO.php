<?php
// Incluimos la conexión a la base de datos principal (Autos Amistosos)
// Usamos la misma ruta que ya confirmamos que funciona.
include_once(__DIR__.'/../../database/conexion_bdd_autos_amistosos.php'); 

class TransaccionVentaDAO { 
    private $conexion;

    public function __construct() {
        // La conexión al objeto BDD debe ser estable.
        $con = new ConexionBDautosAmistosos(); 
        $this->conexion = $con->getConexion();

        if (!$this->conexion) {
            die("Error Fatal: No se pudo conectar a la base de datos para realizar la transacción.");
        }
    }

    public function procesarVenta(array $datosVenta) {
        
        // 1. INICIAR TRANSACCIÓN: Deshabilitar autocommit para agrupar las consultas
        $this->conexion->autocommit(false);
        $ventaExitosa = true; // Flag para monitorear el éxito de todas las operaciones

        // ------------------------------------------------------------------
        // PASO 1: INSERTAR REGISTRO EN LA TABLA VENTA
        // ------------------------------------------------------------------
        $stmtVenta = null;
        try {
            // Asumimos que $datosVenta contiene: 
            // idVendedor, Cliente_idCliente, idAutomovil, Precio_Final, Impuesto_Venta, Costo_Licencia, idGarantia, VIN_Intercambio, Kilometraje_Entrega
            
            // Query de inserción en Venta
           $sqlVenta = "INSERT INTO Venta (Fecha_Venta, Vendedor_idVendedor, Cliente_idCliente, idAutomovil, Precio_Final, Impuesto_Venta, Costo_Licencia, idGarantia, VIN_Intercambio) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
            
            $stmtVenta = $this->conexion->prepare($sqlVenta);
            if (!$stmtVenta) {
                throw new Exception("Error al preparar la consulta de Venta: " . $this->conexion->error);
            }

            // Bind de parámetros (tipos: i=integer, d=double/decimal, s=string)
            $stmtVenta->bind_param("siisddiis", 
                $datosVenta['fecha'],
                $datosVenta['idVendedor'], 
                $datosVenta['idCliente'], 
                $datosVenta['idAutomovil'], 
                $datosVenta['Precio_Final'], 
                $datosVenta['Impuesto_Venta'], 
                $datosVenta['Costo_Licencia'], 
                $datosVenta['idGarantia'], 
                $datosVenta['VIN_Intercambio']
            );
            
            if (!$stmtVenta->execute()) {
                throw new Exception("Error al ejecutar la inserción de Venta: " . $stmtVenta->error);
            }

            // Obtener el ID de la venta recién insertada (necesario si se usa Personalización)
            $idVenta = $this->conexion->insert_id;
            
        } catch (Exception $e) {
            error_log("Fallo en la inserción de Venta: " . $e->getMessage());
            $ventaExitosa = false;
        } finally {
            if ($stmtVenta) $stmtVenta->close();
        }

        // ------------------------------------------------------------------
        // PASO 2: ACTUALIZAR ESTADO DEL AUTOMÓVIL (Solo si el paso 1 fue exitoso)
        // ------------------------------------------------------------------
        if ($ventaExitosa) {
            $stmtAutomovil = null;
            try {
                // Query para actualizar el estado del Automóvil a 'Vendido' y registrar el kilometraje
                $sqlAutomovil = "UPDATE Automovil SET Estado = 'Vendido', Kilometraje_Entrega = ? WHERE idAutomovil = ?";
                
                $stmtAutomovil = $this->conexion->prepare($sqlAutomovil);
                if (!$stmtAutomovil) {
                    throw new Exception("Error al preparar la consulta de Automovil: " . $this->conexion->error);
                }

                $stmtAutomovil->bind_param("is", 
                    $datosVenta['Kilometraje_Entrega'], 
                    $datosVenta['idAutomovil']
                );
                
                if (!$stmtAutomovil->execute()) {
                    throw new Exception("Error al ejecutar la actualización del Automovil: " . $stmtAutomovil->error);
                }
                
                // Verificar si se actualizó una fila
                if ($stmtAutomovil->affected_rows === 0) {
                    throw new Exception("Advertencia: El Automóvil con ID " . $datosVenta['idAutomovil'] . " no fue encontrado para actualizar.");
                }

            } catch (Exception $e) {
                error_log("Fallo en la actualización del Automovil: " . $e->getMessage());
                $ventaExitosa = false;
            } finally {
                if ($stmtAutomovil) $stmtAutomovil->close();
            }
        }
        
        // ------------------------------------------------------------------
        // PASO FINAL: COMMIT O ROLLBACK
        // ------------------------------------------------------------------
        if ($ventaExitosa) {
            $this->conexion->commit(); // Aplicar todos los cambios
            return [
                'success' => true, 
                'message' => "¡Transacción de venta completada con éxito! ID de Venta: " . (isset($idVenta) ? $idVenta : 'N/A')
            ];
        } else {
            $this->conexion->rollback(); // Revertir todos los cambios
            return [
                'success' => false, 
                'message' => "La transacción de venta falló. Todos los cambios han sido revertidos."
            ];
        }
    }

    public function cerrarConexion() {
        if ($this->conexion) {
            // Restaurar autocommit a true al finalizar
            $this->conexion->autocommit(true); 
            $this->conexion->close();
        }
    }
}
?>