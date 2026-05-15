<?php
// Incluimos la clase de conexión que ahora usa PDO
include_once(__DIR__ . '/../database/conexion_bdd_autos_amistosos.php'); 

/**
 * Clase de Acceso a Datos (DAO) para la tabla Cliente_Potencial.
 * Utiliza el Patrón Singleton a través de ConexionBDautosAmistosos para obtener la conexión PDO.
 */
class ClientePotencialDAO {
    
    /** @var PDO $conexion Objeto de conexión PDO real. */
    private $conexion;

    /**
     * Constructor. Obtiene la única instancia de la conexión PDO (Singleton).
     * @throws Exception Si la conexión a la BD falla.
     */
    public function __construct(){
        try {
            // CORRECCIÓN CLAVE: Usar el método estático getInstancia() para obtener el Singleton
            $instancia_singleton = ConexionBDautosAmistosos::getInstancia();
            
            // Obtenemos el objeto PDO real
            $this->conexion = $instancia_singleton->getConexion(); 
            
            if ($this->conexion === null) {
                throw new Exception("Error interno: La conexión PDO obtenida es nula.");
            }
            
        } catch (Exception $e) {
            // Loguear el error y relanzarlo
            error_log("Fallo al instanciar ClientePotencialDAO. Detalle: " . $e->getMessage());
            throw new Exception("Error al inicializar el acceso a la base de datos para Clientes Potenciales.");
        }
    }

    // ***************** ALTAS (A) - INSERTAR *****************
    /**
     * Inserta un nuevo Cliente Potencial usando PDO Prepared Statements.
     * @return int|bool El último ID insertado (int) o False si falla.
     */
    public function insertar($nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        // Ahora usamos directamente $this->conexion, que ya es el objeto PDO
        $sql = "INSERT INTO Cliente_Potencial (Nombre, Apellido1, Apellido2, Direccion, Email, Fuente) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            
            $parametros = [$nombre, $apellido1, $apellido2, $direccion, $email, $fuente];
            $res = $stmt->execute($parametros); 
            
            if ($res) {
                // PDO::lastInsertId() debe ser llamado directamente sobre el objeto PDO
                return $this->conexion->lastInsertId(); 
            } else {
                error_log("Error PDO al ejecutar INSERT: " . print_r($stmt->errorInfo(), true));
                return false;
            }
        } catch (PDOException $e) {
            // Capturamos cualquier error de PDO directamente
            error_log("Excepción PDO al insertar Cliente Potencial: " . $e->getMessage());
            return false;
        }
    }

    // ***************** CONSULTAS (C) - Mostrar Todos *****************
    public function obtenerTodos(){
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                FROM Cliente_Potencial";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
             error_log("Excepción PDO al obtener Clientes Potenciales: " . $e->getMessage());
             return false;
        }
    }

    // ***************** BAJAS (B) - ELIMINAR *****************
    public function eliminar($idClientePotencial){
        $sql = "DELETE FROM Cliente_Potencial WHERE idCliente_Potencial = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idClientePotencial]);
            
            // Retorna true si se eliminó al menos una fila
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
             error_log("Excepción PDO al eliminar Cliente Potencial: " . $e->getMessage());
             return false;
        }
    }

    // ***************** CONSULTAS (C) - Obtener por ID *****************
    public function obtenerPorId($idClientePotencial) {
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                  FROM Cliente_Potencial 
                  WHERE idCliente_Potencial = ?"; 
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idClientePotencial]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
             error_log("Excepción PDO al obtener Cliente Potencial por ID: " . $e->getMessage());
             return false;
        }
    }

    // ***************** CAMBIOS (C) - Actualizar *****************
    public function actualizar($id, $nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        $sql = "UPDATE Cliente_Potencial 
                  SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Direccion = ?, Email = ?, Fuente = ?
                  WHERE idCliente_Potencial = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $parametros = [$nombre, $apellido1, $apellido2, $direccion, $email, $fuente, $id];
            $stmt->execute($parametros);
            
            // Retorna true si se actualizó al menos una fila
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
             error_log("Excepción PDO al actualizar Cliente Potencial: " . $e->getMessage());
             return false;
        }
    }

    // ***************** CONSULTAS (C) - Búsqueda *****************
    public function buscarClientes($termino) {
        $like_term = "%" . $termino . "%";
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                  FROM Cliente_Potencial 
                  WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Email LIKE ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $parametros = [$like_term, $like_term, $like_term];
            $stmt->execute($parametros);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
             error_log("Excepción PDO al buscar Clientes Potenciales: " . $e->getMessage());
             return false;
        }
    }
}
?>