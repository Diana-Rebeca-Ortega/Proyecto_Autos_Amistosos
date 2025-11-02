<?php
include_once('../../database/conexion_bdd_autos_amistosos.php');

class EmpleadoDAO{
    private $conexion;

    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos ();
    }
    //METODOS ABCC (CRUD)
    //*****************ALTAS***************** */
public function agregarEmpleado($nombre, $primerAp, $segundoAp, $idPuesto) {
    
    // 1. Obtener el objeto de conexión
    $conn = $this->conexion->getConexion();

    // 2. Definir la consulta SQL para la tabla EMPLEADOS
    // Nota: Las columnas deben coincidir con tu diseño. Se asume que ID_Empleado es autoincremental.
    $sql = "INSERT INTO EMPLEADOS (Nombre, Primer_Apellido, Segundo_Apellido, ID_Puesto) 
            VALUES (?,?,?,?)";

    // 3. Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Si la preparación falla, retorna falso
    if ($stmt === false) {
        // En un entorno de producción, registrar el error.
        error_log("Error al preparar la consulta: " . $conn->error);
        return false; 
    }

    // 4. Vincular los parámetros y especificar los tipos (s=string, i=integer)
    // ssst: tres strings (nombre, primer apellido, segundo apellido) y un integer (ID_Puesto)
    $stmt->bind_param("sssi", $nombre, $primerAp, $segundoAp, $idPuesto);

    // 5. Ejecutar la sentencia
    $res = $stmt->execute();

    // 6. Cerrar la sentencia
    $stmt->close();

    return $res;
}
//*****************BAJAS***************** */
/*
public function aliminarAlumno($nc){
    $sql = "DELETE FROM alumnos WHERE Num_Control ='$nc'";
    return mysqli_query($this ->conexion->getConexion(), $sql);
}
//*****************CAMBIOS***************** */
//*****************CONSULTAS***************** */
/*
public function  mostrarAlumno (){
  //  $sql = "  select * from alumnos ";  no se recomienda
  $sql = "select  Num_Control, Nombre, Primer_Ap, Segundo_Ap FROM alumnos";
  return mysqli_query($this-> conexion-> get (conexion(), $sql));

}
*/

}
?>