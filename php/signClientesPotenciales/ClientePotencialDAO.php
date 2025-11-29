<?php
include_once(__DIR__ . '/../database/conexion_bdd_autos_amistosos.php'); 

class ClientePotencialDAO {
    private $conexion;

    public function __construct(){
        // Usa la misma clase de conexión que el DAO original
        $this->conexion = new ConexionBDautosAmistosos ();
    }

    // ***************** ALTAS (A) *****************
    /**
     * Inserta un nuevo Cliente Potencial en la base de datos.
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2 (Opcional, puede ser NULL)
     * @param string $direccion (Opcional, puede ser NULL)
     * @param string $email
     * @param string $fuente (Opcional, puede ser NULL)
     * @return bool True si la inserción fue exitosa, False en caso contrario.
     */
    public function insertar($nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        // 1. Obtener el objeto de conexión (mysqli)
        $conn = $this->conexion->getConexion();
        
        // 2. Definir la consulta SQL para la tabla CLIENTE_POTENCIAL
        // Los campos son: Nombre, Apellido1, Apellido2, Direccion, Email, Fuente
        $sql = "INSERT INTO Cliente_Potencial (Nombre, Apellido1, Apellido2, Direccion, Email, Fuente) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta de Alta Cliente Potencial: " . $conn->error);
            return false; 
        }
        
        // 4. Vincular los parámetros y especificar los tipos 
        // ssssss: Seis strings (Nombre, Apellido1, Apellido2, Direccion, Email, Fuente)
        $stmt->bind_param("ssssss", $nombre, $apellido1, $apellido2, $direccion, $email, $fuente);
        
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // ***************** CONSULTAS (C) - Mostrar Todos para las tablas *****************
    /**
     * Obtiene todos los Clientes Potenciales.
     * @return mysqli_result|bool Objeto mysqli_result con los resultados o False si falla.
     */
    public function obtenerTodos(){
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                FROM Cliente_Potencial";
        $res = $conn->query($sql);
        return $res; // Devuelve el objeto mysqli_result
    }

    // ***************** BAJAS (B) *****************
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
             error_log("Error al preparar la consulta de Baja Cliente Potencial: " . $conn->error);
             return false; 
        }
        
        $stmt->bind_param("i", $idClientePotencial); // i = integer
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // ***************** CONSULTAS (C) - Obtener por ID *****************
    /**
     * Obtiene un Cliente Potencial por su ID.
     * @param int $idClientePotencial
     * @return mysqli_result|bool Objeto mysqli_result con el resultado o False si falla.
     */
    public function obtenerPorId($idClientePotencial) {
        $conn = $this->conexion->getConexion();
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                 FROM Cliente_Potencial 
                 WHERE idCliente_Potencial = ?"; 
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
             error_log("Error al preparar la consulta de Obtener Cliente Potencial: " . $conn->error);
             return false; 
        }
        
        $stmt->bind_param("i", $idClientePotencial); 
        $stmt->execute();
        return $stmt->get_result(); 
    }

    // ***************** CAMBIOS (C) - Actualizar *****************
    /**
     * Actualiza la información de un Cliente Potencial.
     * @param int $id
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2
     * @param string $direccion
     * @param string $email
     * @param string $fuente
     * @return bool True si la actualización fue exitosa, False en caso contrario.
     */
    public function actualizar($id, $nombre, $apellido1, $apellido2, $direccion, $email, $fuente) {
        $conn = $this->conexion->getConexion();
        $sql = "UPDATE Cliente_Potencial 
                 SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Direccion = ?, Email = ?, Fuente = ?
                 WHERE idCliente_Potencial = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
             error_log("Error al preparar la consulta de Actualización Cliente Potencial: " . $conn->error);
             return false;
        }
        
        // Vincular parámetros: ssssssi (seis strings y un integer al final para el ID)
        $stmt->bind_param("ssssssi", $nombre, $apellido1, $apellido2, $direccion, $email, $fuente, $id);

        $res = $stmt->execute();
        $stmt->close();
        
        return $res;
    }

    // ***************** CONSULTAS (C) - Búsqueda *****************
    /**
     * Busca Clientes Potenciales por Nombre, Apellido1 o Email.
     * @param string $termino
     * @return mysqli_result|bool Objeto mysqli_result con los resultados o False si falla.
     */
    public function buscarClientes($termino) {
        $conn = $this->conexion->getConexion();
        $like_term = "%" . $termino . "%";
        $sql = "SELECT idCliente_Potencial, Nombre, Apellido1, Apellido2, Direccion, Email, Fuente 
                 FROM Cliente_Potencial 
                 WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Email LIKE ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta de Búsqueda de Clientes Potenciales: " . $conn->error);
            return false; 
        }
        
        $stmt->bind_param("sss", $like_term, $like_term, $like_term); 
        $stmt->execute();
        return $stmt->get_result(); 
    }
}
?>