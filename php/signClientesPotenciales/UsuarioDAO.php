<?php
// AJUSTA ESTA RUTA SEGÚN DÓNDE SE ENCUENTRE tu archivo de conexión
include_once(__DIR__ . '/../database/conexion_bdd_autos_amistosos.php'); 

class UsuarioDAO {
    private $conexion;

    public function __construct(){
        // Usa la misma clase de conexión que el DAO original
        $this->conexion = new ConexionBDautosAmistosos();
    }

    /**
     * Inserta un nuevo usuario para el cliente potencial en la tabla Usuario_Cliente.
     * @param string $email
     * @param string $password_hashed La contraseña ya debe estar cifrada (hashed).
     * @param int $idClientePotencial El ID de la tabla Cliente_Potencial.
     * @return bool True si la inserción fue exitosa, False en caso contrario.
     */
    public function insertarUsuario($email, $password_hashed, $idClientePotencial) {
        
        $conn = $this->conexion->getConexion();
        
        // Usar la tabla Usuario_Cliente creada
        $sql = "INSERT INTO Usuario_Cliente (Email, Password, idCliente_Potencial) 
                 VALUES (?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            error_log("Error al preparar la consulta de Alta de Usuario: " . $conn->error);
            return false; 
        }
        
        // ssi: dos strings (Email, Password) y un integer (idCliente_Potencial)
        $stmt->bind_param("ssi", $email, $password_hashed, $idClientePotencial);
        
        $res = $stmt->execute();
        $stmt->close();

        if (!$res) {
            error_log("Error al ejecutar la inserción del Usuario: " . $stmt->error);
        }

        return $res;
    }
}
?>