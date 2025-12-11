<?php
// AJUSTA ESTA RUTA SEGÚN DÓNDE SE ENCUENTRE tu archivo de conexión
// Asumimos que esta clase ahora usa PDO.
include_once(__DIR__ . '/../database/conexion_bd_usuarios.php'); 

class UsuarioDAO {
    private $conexion;

    public function __construct(){
        // Esta instancia debe devolver un objeto PDO en getConexion()
        $this->conexion = new ConexionBDUsuarios();
    }

   
    public function insertarUsuario($email, $password_hashed, $idClientePotencial) {
        
        $conn = $this->conexion->getConexion(); // Objeto PDO
        
        // Usar la tabla Usuario_Cliente creada
        $sql = "INSERT INTO Usuario_Cliente (Email, Password, idCliente_Potencial) 
                  VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            // Manejo de error de PDO al preparar
            error_log("Error PDO al preparar INSERT de Usuario: " . print_r($conn->errorInfo(), true));
            return false; 
        }
        
        // 1. Definir los parámetros en un array (PDO ya detecta el tipo de dato)
        $parametros = [$email, $password_hashed, $idClientePotencial];
        
        // 2. Ejecutar pasando el array de parámetros
        $res = $stmt->execute($parametros); 
        
        $stmt->closeCursor(); // Cierra el statement de PDO

        if (!$res) {
            // Manejo de error de PDO al ejecutar
            error_log("Error PDO al ejecutar la inserción del Usuario: " . print_r($stmt->errorInfo(), true));
        }

        return $res;
    }
}
?>