<?php
// Incluimos la conexión a la base de datos principal (Autos Amistosos)
// Asegúrate de que esta ruta y el archivo devuelvan un objeto PDO.
require_once(__DIR__.'../../../database/conexion_bdd_autos_amistosos.php'); 

class TransaccionVentaDAO { 
    private $conexion; // Será un objeto PDO

    public function __construct() {
       
       $this->conexion = ConexionBDautosAmistosos::getInstancia()->getConexion();

        if (!$this->conexion) {
            // Este error ya no debería ocurrir si el Singleton funciona, pero es una buena práctica.
            die("Error Fatal: No se pudo conectar a la base de datos (PDO) para realizar la transacción.");
        }
    }

    public function procesarVenta(array $datosVenta) {
        
        // 1. INICIAR TRANSACCIÓN: Usando la sintaxis PDO
        $this->conexion->beginTransaction(); // Inicia la transacción
        $ventaExitosa = true; 
        $idVenta = null;
        
        // ------------------------------------------------------------------
        // PASO 1: INSERTAR REGISTRO EN LA TABLA VENTA
        // ------------------------------------------------------------------
        $stmtVenta = null;
        try {
            // Query de inserción en Venta
            // Usamos PLACEHOLDERS (?)
            $sqlVenta = "INSERT INTO Venta (Fecha_Venta, Vendedor_idVendedor, Cliente_idCliente, idAutomovil, Precio_Final, Impuesto_Venta, Costo_Licencia, idGarantia, VIN_Intercambio) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
            
            $stmtVenta = $this->conexion->prepare($sqlVenta);
            if (!$stmtVenta) {
                // PDO::errorInfo() es la forma correcta de obtener errores en PDO
                throw new Exception("Error al preparar la consulta de Venta: " . json_encode($this->conexion->errorInfo()));
            }

            // Execute (PDO deduce el tipo de dato automáticamente)
            $executeResult = $stmtVenta->execute([
                $datosVenta['fecha'],
                $datosVenta['idVendedor'], 
                $datosVenta['idCliente'], 
                $datosVenta['idAutomovil'], 
                $datosVenta['Precio_Final'], 
                $datosVenta['Impuesto_Venta'], 
                $datosVenta['Costo_Licencia'], 
               $datosVenta['idGarantia'] === '' || $datosVenta['idGarantia'] === 'Ninguna (NULL)' ? null : $datosVenta['idGarantia'], // Maneja NULL si está vacío
                $datosVenta['VIN_Intercambio'] === '' ? null : $datosVenta['VIN_Intercambio'] 
           
            ]);
            
            if (!$executeResult) {
                throw new Exception("Error al ejecutar la inserción de Venta: " . json_encode($stmtVenta->errorInfo()));
            }

            // Obtener el ID de la venta recién insertada (PDO)
            $idVenta = $this->conexion->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Fallo en la inserción de Venta: " . $e->getMessage());
            $ventaExitosa = false;
        } finally {
            if ($stmtVenta) $stmtVenta->closeCursor(); // Cierra el cursor de PDO
        }

        // ------------------------------------------------------------------
        // PASO 2: ACTUALIZAR ESTADO DEL AUTOMÓVIL (Solo si el paso 1 fue exitoso)
        // ------------------------------------------------------------------
        if ($ventaExitosa) {
            $stmtAutomovil = null;
            try {
                // Query para actualizar el estado del Automóvil a 'VENDIDO' (usando el valor de tu tabla) y Kilometraje_Entrega
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
                
                // Verificar si se actualizó una fila
                if ($stmtAutomovil->rowCount() === 0) { // PDO utiliza rowCount()
                    throw new Exception("Advertencia: El Automóvil con ID " . $datosVenta['idAutomovil'] . " no fue encontrado para actualizar.");
                }

            } catch (Exception $e) {
                error_log("Fallo en la actualización del Automovil: " . $e->getMessage());
                $ventaExitosa = false;
            } finally {
                if ($stmtAutomovil) $stmtAutomovil->closeCursor(); // Cierra el cursor de PDO
            }
        }
        
        // ------------------------------------------------------------------
        // PASO FINAL: COMMIT O ROLLBACK (Usando la sintaxis PDO)
        // ------------------------------------------------------------------
        if ($ventaExitosa) {
            $this->conexion->commit(); // Aplicar todos los cambios
            return [
                'success' => true, 
                'message' => "¡Transacción de venta completada con éxito! ID de Venta: " . $idVenta
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
        // En PDO, simplemente se elimina la referencia a la conexión.
        $this->conexion = null; 
    }
}
?>