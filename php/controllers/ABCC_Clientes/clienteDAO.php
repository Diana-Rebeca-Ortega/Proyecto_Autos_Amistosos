<?php

include_once(__DIR__ . '../../../database/conexion_bdd_autos_amistosos.php'); 

class ClienteDAO {
    private $conexion; // Este será el objeto PDO

    public function __construct(){
        // USO CORRECTO DEL SINGLETON (Punto 8)
        $this->conexion = ConexionBDautosAmistosos::getInstancia()->getConexion();
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