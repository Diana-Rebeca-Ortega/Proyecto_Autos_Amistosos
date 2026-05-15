<?php
// VentaDAO.php o TransaccionVentaDAO.php (depende de cómo decidas nombrar tu archivo)

// Incluimos la conexión a la base de datos principal
// Asegúrate de que esta ruta sea correcta
require_once(__DIR__.'../../../database/conexion_bdd_autos_amistosos.php'); 

class VentaDAO { // Se renombra a VentaDAO para ser más general
    
    private $conexion; // Objeto PDO

    public function __construct() {
        // Obtenemos la conexión PDO del Singleton
        $instancia_singleton = ConexionBDautosAmistosos::getInstancia();
        
        // Verificamos si la instancia es nula (posible fallo de conexión en Singleton)
        if ($instancia_singleton === null) {
            die("Error Fatal: La instancia SINGLETON de conexión es NULA en VentaDAO.");
        }
        
        $this->conexion = $instancia_singleton->getConexion();

        if (!$this->conexion) {
            die("Error Fatal: No se pudo obtener la conexión PDO en VentaDAO.");
        }
    }

    // ===================================================================
    // MÉTODO CRÍTICO PARA ELIMINACIÓN DE CLIENTES (Baja en cascada lógica)
    // ===================================================================
    
    /**
     * Elimina todos los registros de la tabla Venta asociados a un Cliente.
     * Esto se utiliza para romper la dependencia de Clave Foránea antes de eliminar el Cliente.
     * @param int $idCliente El ID del cliente cuyas ventas se van a eliminar.
     * @return bool True si la ejecución fue exitosa, False en caso de error PDO.
     */
    public function eliminarVentasPorCliente($idCliente){
        $sql = "DELETE FROM Venta WHERE Cliente_idCliente = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            // El resultado de execute() es un booleano (TRUE en éxito, FALSE en fallo de ejecución)
            return $stmt->execute([$idCliente]); 
            
        } catch (PDOException $e) {
            // Registramos el error de la BD, que puede ser útil para la depuración
            error_log("Error al eliminar ventas por cliente " . $idCliente . ": " . $e->getMessage());
            return false; 
        }
    }

    // ===================================================================
    // MÉTODO DE PROCESAMIENTO DE VENTA (Transacción - Basado en tu código original)
    // ===================================================================

    public function procesarVenta(array $datosVenta) {
        
        // 1. INICIAR TRANSACCIÓN
        try {
            $this->conexion->beginTransaction(); 
        } catch (PDOException $e) {
             error_log("Error al iniciar la transacción: " . $e->getMessage());
             return ['success' => false, 'message' => "No se pudo iniciar la transacción."];
        }

        $ventaExitosa = true; 
        $idVenta = null;
        
        // PASO 1: INSERTAR REGISTRO EN LA TABLA VENTA
        $stmtVenta = null;
        try {
            // ... (Tu lógica original de inserción de Venta)
            $sqlVenta = "INSERT INTO Venta (Fecha_Venta, Vendedor_idVendedor, Cliente_idCliente, idAutomovil, Precio_Final, Impuesto_Venta, Costo_Licencia, idGarantia, VIN_Intercambio) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
            
            $stmtVenta = $this->conexion->prepare($sqlVenta);
            
            // Revisa si la preparación falla (aunque PDO generalmente lanza una excepción aquí si hay error de sintaxis)
            if (!$stmtVenta) {
                throw new Exception("Error al preparar la consulta de Venta: " . json_encode($this->conexion->errorInfo()));
            }

            $executeResult = $stmtVenta->execute([
                $datosVenta['fecha'],
                $datosVenta['idVendedor'], 
                $datosVenta['idCliente'], 
                $datosVenta['idAutomovil'], 
                $datosVenta['Precio_Final'], 
                $datosVenta['Impuesto_Venta'], 
                $datosVenta['Costo_Licencia'], 
                // Manejo de NULLs para campos opcionales
                $datosVenta['idGarantia'] === '' || $datosVenta['idGarantia'] === 'Ninguna (NULL)' ? null : $datosVenta['idGarantia'], 
                $datosVenta['VIN_Intercambio'] === '' ? null : $datosVenta['VIN_Intercambio'] 
            ]);
            
            if (!$executeResult) {
                throw new Exception("Error al ejecutar la inserción de Venta: " . json_encode($stmtVenta->errorInfo()));
            }

            $idVenta = $this->conexion->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Fallo en la inserción de Venta: " . $e->getMessage());
            $ventaExitosa = false;
        } finally {
             // Cierra el cursor de PDO (aunque no siempre es estrictamente necesario para sentencias simples)
            if ($stmtVenta) $stmtVenta->closeCursor(); 
        }

        // PASO 2: ACTUALIZAR ESTADO DEL AUTOMÓVIL
        if ($ventaExitosa) {
            $stmtAutomovil = null;
            try {
                // ... (Tu lógica original de actualización de Automovil)
                $sqlAutomovil = "UPDATE Automovil SET Estado = 'VENDIDO', Kilometraje_Entrega = ? WHERE idAutomovil = ?";
                
                $stmtAutomovil = $this->conexion->prepare($sqlAutomovil);
                if (!$stmtAutomovil) {
                    throw new Exception("Error al preparar la consulta de Automovil: " . json_encode($this->conexion->errorInfo()));
                }

                $executeResult = $stmtAutomovil->execute([
                    $datosVenta['Kilometraje_Entrega'], 
                    $datosVenta['idAutomovil']
                ]);
                
                if (!$executeResult) {
                    throw new Exception("Error al ejecutar la actualización del Automovil: " . json_encode($stmtAutomovil->errorInfo()));
                }
                
                if ($stmtAutomovil->rowCount() === 0) { 
                    throw new Exception("Advertencia: El Automóvil con ID " . $datosVenta['idAutomovil'] . " no fue encontrado para actualizar.");
                }

            } catch (Exception $e) {
                error_log("Fallo en la actualización del Automovil: " . $e->getMessage());
                $ventaExitosa = false;
            } finally {
                if ($stmtAutomovil) $stmtAutomovil->closeCursor();
            }
        }
        
        // PASO FINAL: COMMIT O ROLLBACK
        if ($ventaExitosa) {
            $this->conexion->commit();
            return [
                'success' => true, 
                'message' => "¡Transacción de venta completada con éxito! ID de Venta: " . $idVenta
            ];
        } else {
            $this->conexion->rollback(); 
            return [
                'success' => false, 
                'message' => "La transacción de venta falló. Todos los cambios han sido revertidos."
            ];
        }
    }

    public function cerrarConexion() {
        $this->conexion = null; 
    }
}
?>