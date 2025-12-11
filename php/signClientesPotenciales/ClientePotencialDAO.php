<?php
// Incluimos la clase de conexión que ahora usa PDO
include_once(__DIR__ . '/../database/conexion_bdd_autos_amistosos.php'); 

class ClientePotencialDAO {
    private $conexion;

    public function __construct(){
        // Crea una instancia de la clase que gestiona la conexión PDO
        $this->conexion = new ConexionBDautosAmistosos();
    }

    // ***************** ALTAS (A) - INSERTAR *****************
    /**
     * Inserta un nuevo Cliente Potencial usando PDO Prepared Statements.
     * @param string $nombre, $apellido1, $apellido2, $direccion, $email, $fuente
     * @return int|bool El último ID insertado o False si falla.
     */
    public function insertar($nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        $conn = $this->conexion->getConexion(); // Objeto PDO
        
        $sql = "INSERT INTO Cliente_Potencial (Nombre, Apellido1, Apellido2, Direccion, Email, Fuente) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error PDO al preparar INSERT: " . print_r($conn->errorInfo(), true));
            return false; 
        }
        
        $parametros = [$nombre, $apellido1, $apellido2, $direccion, $email, $fuente];
        $res = $stmt->execute($parametros); // Ligar y ejecutar en PDO
        
        if ($res) {
            $ultimo_id = $conn->lastInsertId(); // Función de PDO para obtener el último ID
            $stmt->closeCursor(); 
            return $ultimo_id; 
        } else {
            error_log("Error PDO al ejecutar INSERT: " . print_r($stmt->errorInfo(), true));
            $stmt->closeCursor();
            return false;
        }
    }

    // ***************** CONSULTAS (C) - Mostrar Todos *****************
    /**
     * Obtiene todos los Clientes Potenciales.
     * @return array|bool Array asociativo con los resultados o False si falla.
     */
    public function obtenerTodos(){
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                FROM Cliente_Potencial";
        
        // Usamos prepare/execute incluso sin parámetros para consistencia PDO
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // fetchAll devuelve todas las filas como un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt->closeCursor();
        return $resultados; 
    }

    // ***************** BAJAS (B) - ELIMINAR *****************
    /**
     * Elimina un Cliente Potencial por su ID.
     * @param int $idClientePotencial
     * @return bool True si la eliminación fue exitosa, False en caso contrario.
     */
    public function eliminar($idClientePotencial){
        $conn = $this->conexion->getConexion();
        $sql = "DELETE FROM Cliente_Potencial WHERE idCliente_Potencial = ?";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
             error_log("Error PDO al preparar DELETE: " . print_r($conn->errorInfo(), true));
             return false; 
        }
        
        $res = $stmt->execute([$idClientePotencial]); // Ejecución simple con array
        $stmt->closeCursor();
        
        // PDOStatement::execute devuelve true en éxito
        return $res;
    }

    // ***************** CONSULTAS (C) - Obtener por ID *****************
    /**
     * Obtiene un Cliente Potencial por su ID.
     * @param int $idClientePotencial
     * @return array|bool Array asociativo con el resultado o False si falla/no existe.
     */
    public function obtenerPorId($idClientePotencial) {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                  FROM Cliente_Potencial 
                  WHERE idCliente_Potencial = ?"; 
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
             error_log("Error PDO al preparar SELECT por ID: " . print_r($conn->errorInfo(), true));
             return false; 
        }
        
        $stmt->execute([$idClientePotencial]);
        
        // fetch devuelve solo la primera fila
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        
        return $resultado; // Devuelve un array o false si no encuentra nada
    }

    // ***************** CAMBIOS (C) - Actualizar *****************
    /**
     * Actualiza la información de un Cliente Potencial.
     * @return bool True si la actualización fue exitosa, False en caso contrario.
     */
    public function actualizar($id, $nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        $conn = $this->conexion->getConexion();
        $sql = "UPDATE Cliente_Potencial 
                  SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Direccion = ?, Email = ?, Fuente = ?
                  WHERE idCliente_Potencial = ?";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
             error_log("Error PDO al preparar UPDATE: " . print_r($conn->errorInfo(), true));
             return false;
        }
        
        // Los parámetros deben ir en el mismo orden que los signos '?' en el SQL
        $parametros = [$nombre, $apellido1, $apellido2, $direccion, $email, $fuente, $id];

        $res = $stmt->execute($parametros);
        $stmt->closeCursor();
        
        return $res;
    }

    // ***************** CONSULTAS (C) - Búsqueda *****************
    /**
     * Busca Clientes Potenciales por Nombre, Apellido1 o Email.
     * @param string $termino
     * @return array|bool Array asociativo con los resultados o False si falla.
     */
    public function buscarClientes($termino) {
        $conn = $this->conexion->getConexion();
        $like_term = "%" . $termino . "%";
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                  FROM Cliente_Potencial 
                  WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Email LIKE ?";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error PDO al preparar la Búsqueda: " . print_r($conn->errorInfo(), true));
            return false; 
        }
        
        // Tres parámetros LIKE
        $parametros = [$like_term, $like_term, $like_term];
        $stmt->execute($parametros);

        // Obtener todos los resultados de la búsqueda
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        
        return $resultados; 
    }
}
?>