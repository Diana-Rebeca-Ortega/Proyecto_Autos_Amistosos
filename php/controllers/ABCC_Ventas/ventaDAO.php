<?php
// Asegúrate de cambiar el path a tu archivo de conexión si es necesario
// Si tu archivo de conexión está en el mismo nivel, puedes usar solo 'conexion.php'
include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php');

class VentaDAO {
    private $conexion; // Almacena la instancia de la clase Conexion

 
    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos ();
    }

 
    // CÓDIGO CORREGIDO (Omitiendo idVenta)

// Los parámetros en la función deben ser 9 (sin idVenta)
public function registrarVenta($fecha, $precio, $impuesto, $costo_licencia, $idVendedor, $idCliente, $idAutomovil, $idGarantia, $vinIntercambio) {
    
    $conn = $this->conexion->getConexion();
    
    // 1. Sentencia SQL con 8 marcadores de posición (?)
    // Nota: idVenta se omite para que el motor de BD lo genere (auto_increment)
    $sql = "INSERT INTO Venta (
                Fecha_Venta, Precio_Final, Impuesto_Venta, Costo_Licencia, 
                Vendedor_idVendedor, Cliente_idCliente, idAutomovil, 
                idGarantia, VIN_Intercambio
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        error_log("Error al preparar la consulta de Venta: " . $conn->error);
        return false;
    }
    // 2. Bindear parámetros: Ahora son 9 parámetros (sdddiisss)
    // Los tipos siguen siendo los mismos, ya que estás pasando 9 variables:
    // s (Fecha) ddd (Precios) i i s (FKs obligatorias) s s (FKs opcionales)
    $stmt->bind_param("sdddiisss", 
        $fecha, 
        $precio, 
        $impuesto, 
        $costo_licencia, 
        $idVendedor, 
        $idCliente, 
        $idAutomovil, 
        $idGarantia,       
        $vinIntercambio    
    );
    
    $resultado = $stmt->execute(); 
    $stmt->close();
    
    return $resultado;
}
}
?>