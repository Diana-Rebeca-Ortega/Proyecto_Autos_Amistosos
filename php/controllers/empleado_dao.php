<?php
include_once(__DIR__ . '../../database/conexion_bdd_autos_amistosos.php');

class EmpleadoDAO{
    private $conexion;

    public function __construct(){
        $this->conexion = new ConexionBDautosAmistosos ();
    }
    //METODOS ABCC (CRUD)
    //*****************ALTAS***************** */
 public function agregarEmpleado($nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision) {   
    // 1. Obtener el objeto de conexión
    $conn = $this->conexion->getConexion();
    // 2. Definir la consulta SQL para la tabla EMPLEADOS
   $sql = "INSERT INTO Vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión) 
        VALUES (?, ?, ?, ?, ?)";
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
    $stmt->bind_param('sssdd', $nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision);
    // 5. Ejecutar la sentencia
    $res = $stmt->execute();
    // 6. Cerrar la sentencia
    $stmt->close();
    return $res;
}

  public function mostrarEmpleado($filtro){
            //$sql = "SELECT * FROM alumnos";
           $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión
            FROM Vendedor";
            return mysqli_query($this->conexion->getConexion(), $sql);
        }

public function eliminarEmpleado($id_vendedor){
    $sql = "DELETE FROM Vendedor WHERE idVendedor ='$id_vendedor'";
    return mysqli_query($this ->conexion->getConexion(), $sql);
}

public function getEmpleadoByID($id_empleado) {
    $conn = $this->conexion->getConexion();
    
    // Consulta segura para buscar un solo empleado por su ID
    $sql = "SELECT ID_Empleado, Nombre, Primer_Apellido, Segundo_Apellido, ID_Puesto 
            FROM EMPLEADOS 
            WHERE ID_Empleado = ?"; 

    $stmt = $conn->prepare($sql);

    // Bind: 'i' porque ID_Empleado es un entero
    $stmt->bind_param("i", $id_empleado); 

    $stmt->execute();
    
    // Devolvemos el resultado para que el script de edición lo use
    return $stmt->get_result(); 
}


// En EmpleadoDAO.php

public function actualizarEmpleado($id, $nombre, $primerAp, $segundoAp, $idPuesto) {
    $conn = $this->conexion->getConexion();
    
    // Consulta SQL para actualizar los campos
    $sql = "UPDATE EMPLEADOS 
            SET Nombre = ?, Primer_Apellido = ?, Segundo_Apellido = ?, ID_Puesto = ?
            WHERE ID_Empleado = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error al preparar la consulta de actualización: " . $conn->error);
        return false;
    }

    // Vincular parámetros: sssii (tres strings y dos integers, el último es el ID)
    $stmt->bind_param("sssii", $nombre, $primerAp, $segundoAp, $idPuesto, $id);

    $res = $stmt->execute();

    $stmt->close();
    
    return $res;
}

}
?>