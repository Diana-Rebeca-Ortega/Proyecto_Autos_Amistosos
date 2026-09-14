<?php
// AJUSTA ESTA RUTA SEGÚN DÓNDE SE ENCUENTRE tu archivo de conexión
// Asegúrate de que esta clase sea el Singleton (como ConexionBDautosAmistosos)
include_once(__DIR__ . '/../database/conexion_bd_usuarios.php'); 

class UsuarioDAO {
    
    /** @var PDO $conexion Objeto de conexión PDO real. */
    private $conexion;

    /**
     * Constructor. Obtiene la única instancia de la conexión PDO (Singleton).
     * @throws Exception Si la conexión a la BD falla.
     */
    public function __construct(){
        try {
            // CORRECCIÓN CLAVE: Usar el método estático getInstancia() para obtener el Singleton
            // Asumimos que el método para obtener la instancia única se llama getInstancia()
            $instancia_singleton = ConexionBDUsuarios::getInstancia();
            
            // Obtenemos el objeto PDO real de la conexión
            $this->conexion = $instancia_singleton->getConexion();
            
            if ($this->conexion === null) {
                throw new Exception("Error interno: La conexión PDO para usuarios es nula.");
            }
            
        } catch (Exception $e) {
            // Loguear el error y relanzarlo para que el código llamador lo maneje
            error_log("Fallo al instanciar UsuarioDAO. Detalle: " . $e->getMessage());
            throw new Exception("Error al inicializar el acceso a la base de datos de usuarios.");
        }
    }

    // ***************** ALTAS (A) - INSERTAR *****************
    /**
     * Inserta un nuevo Usuario asociado a un Cliente Potencial.
     * @param string $email, $password_hashed
     * @param int $idClientePotencial
     * @return bool True si la inserción fue exitosa, False en caso contrario.
     */
    public function insertarUsuario($email, $password_hashed, $idClientePotencial) {
        
        // Ahora usamos directamente $this->conexion, que ya es el objeto PDO
        $sql = "INSERT INTO Usuario_Cliente (Email, Password, idCliente_Potencial) 
                 VALUES (?, ?, ?)";
        
        try {
            $stmt = $this->conexion->prepare($sql);
            
            // Los parámetros se ligan y ejecutan en un solo paso
            $parametros = [$email, $password_hashed, $idClientePotencial];
            
            // PDO lanza una excepción si hay un error en la ejecución (ej: clave duplicada)
            $res = $stmt->execute($parametros); 
            
            return $res;
            
        } catch (PDOException $e) {
            // Capturamos cualquier error de PDO directamente
            error_log("Excepción PDO al insertar Usuario: " . $e->getMessage());
            
            // Si quieres ver detalles del error, descomenta esto (solo en desarrollo)
            // error_log("Detalles SQL: " . print_r($stmt->errorInfo(), true));
            
            return false;
        }
    }
    
    // Aquí irían otros métodos como obtenerUsuarioPorEmail(), actualizarPassword(), etc.
}
?>