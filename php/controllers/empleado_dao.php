<?php
// Asegúrate de que esta clase de conexión usa PDO y tiene el método getInstancia()
include_once(__DIR__ . '../../database/conexion_bdd_autos_amistosos.php');

class EmpleadoDAO{
    private $conexion;

    // El constructor usa el Singleton para obtener la única instancia de conexión PDO
    public function __construct(){
        $this->conexion = ConexionBDautosAmistosos::getInstancia();
    }

    // METODOS ABCC (CRUD)
    // *****************ALTAS*****************
    public function agregarEmpleado($nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision) {
        $conn = $this->conexion->getConexion(); // Objeto PDO
        
        $sql = "INSERT INTO Vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión) 
                 VALUES (?, ?, ?, ?, ?)";
        
        try {
            $stmt = $conn->prepare($sql);
            
            // PDO execute() con un array de parámetros
            $res = $stmt->execute([
                $nombre, 
                $apellido1, 
                $apellido2, 
                $salario_base, 
                $porcentaje_comision
            ]);
            
            $stmt = null; 
            return $res;
            
        } catch (PDOException $e) {
            error_log("Error de PDO en agregarEmpleado: " . $e->getMessage());
            return false;
        }
    }

    // *****************CONSULTA/MOSTRAR*****************
    // Retorna el objeto PDOStatement para que el consumidor pueda iterar sobre él
    public function mostrarEmpleado() { // Eliminamos $filtro ya que no se usa
        $conn = $this->conexion->getConexion();        
        $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión FROM Vendedor";
        
        try {
            // Usamos query() para SELECT sin parámetros
            $stmt = $conn->query($sql);
            return $stmt; // Retorna PDOStatement
        } catch (PDOException $e) {
            error_log("Error de PDO en mostrarEmpleado: " . $e->getMessage());
            return false;
        }
    }

    // *****************ELIMINAR*****************
    public function eliminarEmpleado($id_vendedor) {
        $conn = $this->conexion->getConexion();
        $sql = "DELETE FROM Vendedor WHERE idVendedor = ?";
        
        try {
            $stmt = $conn->prepare($sql);
            // Ejecuta la sentencia DELETE
            $res = $stmt->execute([$id_vendedor]);
            $stmt = null;
            return $res;
        } catch (PDOException $e) {
            error_log("Error de PDO en eliminarEmpleado: " . $e->getMessage());
            return false;
        }
    }

    // *****************OBTENER POR ID*****************
    // Retorna un único registro (como array asociativo)
    public function getEmpleadoByID($id_vendedor) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión 
                FROM Vendedor 
                WHERE idVendedor = ?";

        try {
            $stmt = $conn->prepare($sql);
            // Ejecuta la sentencia
            $stmt->execute([$id_vendedor]); 
            
            // PDOStatement::fetch() obtiene una única fila
            return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna el empleado como array asociativo o false
        } catch (PDOException $e) {
            error_log("Error de PDO en getEmpleadoByID: " . $e->getMessage());
            return false;
        }
    }

    // *****************ACTUALIZAR*****************
    public function actualizarEmpleado($id, $nombre, $primerAp, $segundoAp, $salarioBase, $porcentajeComision) {
        $conn = $this->conexion->getConexion();
        
        $sql = "UPDATE Vendedor
                SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comisión = ?
                WHERE idVendedor = ?";

        try {
            $stmt = $conn->prepare($sql);
            
            // Array de parámetros en el orden de los marcadores '?'
            $res = $stmt->execute([
                $nombre, 
                $primerAp, 
                $segundoAp, 
                $salarioBase, 
                $porcentajeComision,
                $id // ID al final
            ]);

            $stmt = null;
            return $res;

        } catch (PDOException $e) {
            error_log("Error de PDO en actualizarEmpleado: " . $e->getMessage());
            return false;
        }
    }

    // *****************OBTENER TODOS (Sin filtro)*****************
    public function obtenerTodos(){
        // Este método es idéntico a mostrarEmpleado() pero lo mantendremos
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión FROM Vendedor";
        
        try {
            $stmt = $conn->query($sql);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error de PDO en obtenerTodos: " . $e->getMessage());
            return false;
        }
    }

    // *****************BUSCAR VENDEDORES*****************
    public function buscarVendedores($termino) {
        $conn = $this->conexion->getConexion();
        $like_term = "%" . $termino . "%";
        
        $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión 
                FROM Vendedor 
                WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Apellido2 LIKE ?"; 
        
        try {
            $stmt = $conn->prepare($sql);
            
            // Se ejecutan los parámetros, pasando el término de búsqueda 3 veces
            $stmt->execute([$like_term, $like_term, $like_term]); 
            
            // Retorna el objeto PDOStatement
            return $stmt; 
            
        } catch (PDOException $e) {
            error_log("Error de PDO en buscarVendedores: " . $e->getMessage());
            return false;
        }
    }
}
?>