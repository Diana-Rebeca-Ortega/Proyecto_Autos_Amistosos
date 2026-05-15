<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class ClienteDAO {
  
    private $conexion; 

  public function __construct(){
    $instancia_singleton = ConexionBDautosAmistosos::getInstancia();

    if ($instancia_singleton === null) {
        // La instancia falló en su creación (por error de conexión).
        $this->conexion = null;
        die("Error Fatal: La instancia SINGLETON de conexión es NULA en ClienteDAO. (Revisa credenciales de BD)");
    }
    
    // Si la instancia existe, obtenemos la conexión (que no debería ser null si no falló la creación)
    $this->conexion = $instancia_singleton->getConexion();
    
    if (!$this->conexion) {
        // Esto captura la conexión interna nula (aunque es improbable con la Modificación 1).
        die("Error Fatal: La conexión interna es nula en ClienteDAO.");
    }
}

    // ***************** ALTAS (A) *****************
    public function insertar($nombre, $apellido1, $apellido2, $direccion, $telefono, $email) {
        $sql = "INSERT INTO Cliente (Nombre, Apellido1, Apellido2, Direccion, Telefono, Email) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            // PDO: preparamos la consulta directamente
            $stmt = $this->conexion->prepare($sql); 
            
            // PDO: Ejecutamos pasando los parámetros en un array (se gestionan los tipos automáticamente)
            return $stmt->execute([$nombre, $apellido1, $apellido2, $direccion, $telefono, $email]);
            
        } catch (PDOException $e) {
            error_log("Error al insertar Cliente: " . $e->getMessage());
            return false;
        }
    }
    
    // ***************** CONSULTAS (C) - Mostrar Todos para las tablas *****************
    public function obtenerTodos(){
        $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email FROM Cliente";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            
            // PDO: Devolvemos un array asociativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error al obtener todos los Clientes: " . $e->getMessage());
            return [];
        }
    }
    
    // ***************** BAJAS (B) *****************
    public function eliminar($idCliente){
        $sql = "DELETE FROM Cliente WHERE idCliente = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            
            // PDO: El resultado de execute ya es un booleano
            return $stmt->execute([$idCliente]);
            
        } catch (PDOException $e) {
            error_log("Error al eliminar Cliente: " . $e->getMessage());
            return false;
        }
    }
    
    // ***************** CAMBIOS (C) - Obtener por ID *****************
    public function obtenerPorId($idCliente) {
        $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email 
                FROM Cliente 
                WHERE idCliente = ?"; 
        
        try {
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$idCliente]);
            
            // PDO: Devuelve la primera fila
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error al obtener Cliente por ID: " . $e->getMessage());
            return false; 
        }
    }
    
    // ***************** CAMBIOS (C) - Actualizar *****************
    public function actualizar($id, $nombre, $apellido1, $apellido2, $direccion, $telefono, $email) {
        $sql = "UPDATE Cliente 
                SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Direccion = ?, Telefono = ?, Email = ?
                WHERE idCliente = ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            
            // Todos los parámetros en orden, incluyendo el ID al final
            return $stmt->execute([$nombre, $apellido1, $apellido2, $direccion, $telefono, $email, $id]);
            
        } catch (PDOException $e) {
            error_log("Error al actualizar Cliente: " . $e->getMessage());
            return false;
        }
    }
    
    // ***************** BÚSQUEDA *****************
    public function buscarClientes($termino) {
        $like_term = "%" . $termino . "%";
        $sql = "SELECT idCliente, Nombre, Apellido1, Apellido2, Direccion, Telefono, Email 
                FROM Cliente 
                WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Email LIKE ?";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            // Pasar el término LIKE tres veces
            $stmt->execute([$like_term, $like_term, $like_term]);
            
            // PDO: Devuelve el array de resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar Clientes: " . $e->getMessage());
            return []; 
        }
    }
}
?>