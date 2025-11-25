<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class ClienteDAO {
    private $conexion;

    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos ();
    }

    // ***************** ALTAS (A) *****************
    public function insertar($nombre, $apellido1, $apellido2, $direccion, $telefono, $email) {
        // 1. Obtener el objeto de conexión (mysqli)
        $conn = $this->conexion->getConexion();
        // 2. Definir la consulta SQL para la tabla CLIENTE
        $sql = "INSERT INTO Cliente (Nombre, Apellido1, Apellido2, Direccion, Telefono, Email) 
                VALUES (?, ?, ?, ?, ?, ?)";
        // 3. Preparar la sentencia
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("Error al preparar la consulta de Alta Cliente: " . $conn->error);
            return false; 
        }
        // 4. Vincular los parámetros y especificar los tipos 
        // ssssss: Seis strings (Nombre, Apellido1, Apellido2, Direccion, Telefono, Email)
        $stmt->bind_param("ssssss", $nombre, $apellido1, $apellido2, $direccion, $telefono, $email);
        // 5. Ejecutar la sentencia
        $res = $stmt->execute();
        // 6. Cerrar la sentencia
        $stmt->close();
        return $res;
    }
     // ***************** CONSULTAS (C) - Mostrar Todos para las tablas *****************
    public function obtenerTodos(){
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email FROM Cliente";
        
        // Usamos query() para una SELECT sin parámetros (es más sencillo)
        $res = $conn->query($sql);
        
        return $res; // Devuelve el objeto mysqli_result
    }
// ***************** BAJAS (B) *****************
    public function eliminar($idCliente){
        $conn = $this->conexion->getConexion();
        $sql = "DELETE FROM Cliente WHERE idCliente = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
             error_log("Error al preparar la consulta de Baja Cliente: " . $conn->error);
             return false; 
        }
        $stmt->bind_param("i", $idCliente); // i = integer
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

       // ***************** CAMBIOS (C) - Obtener por ID *****************
    public function obtenerPorId($idCliente) {
        $conn = $this->conexion->getConexion();
        
        $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email 
                FROM Cliente 
                WHERE idCliente = ?"; 

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
             error_log("Error al preparar la consulta de Obtener Cliente: " . $conn->error);
             return false; 
        }

        $stmt->bind_param("i", $idCliente); 
        $stmt->execute();
        
        // Devolvemos el resultado (mysqli_result)
        return $stmt->get_result(); 
    }

    // ***************** CAMBIOS (C) - Actualizar *****************
    public function actualizar($id, $nombre, $apellido1, $apellido2, $direccion, $telefono, $email) {
        $conn = $this->conexion->getConexion();
        
        $sql = "UPDATE Cliente 
                SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Direccion = ?, Telefono = ?, Email = ?
                WHERE idCliente = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
             error_log("Error al preparar la consulta de Actualización Cliente: " . $conn->error);
             return false;
        }

        // Vincular parámetros: ssssssi (seis strings y un integer al final para el ID)
        $stmt->bind_param("ssssssi", $nombre, $apellido1, $apellido2, $direccion, $telefono, $email, $id);

        $res = $stmt->execute();
        $stmt->close();
        
        return $res;
    }
public function buscarClientes($termino) {
    // 1. Obtener la conexión
    $conn = $this->conexion->getConexion();
    // 2. Preparar el término de búsqueda para LIKE
    $like_term = "%" . $termino . "%";
    // 3. Consulta SQL con búsqueda
    $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email 
            FROM Cliente 
            WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Email LIKE ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error al preparar la consulta de Búsqueda: " . $conn->error);
        return false; 
    }
    $stmt->bind_param("sss", $like_term, $like_term, $like_term); 
    
    $stmt->execute();
    
    // Devolver el resultado
    return $stmt->get_result(); 
}
}
?>