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

public function getEmpleadoByID($id_vendedor) {
    $conn = $this->conexion->getConexion();
    
 $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión 
            FROM Vendedor 
            WHERE idVendedor = ?";

    $stmt = $conn->prepare($sql);

    // Bind: 'i' porque ID_Empleado es un entero
    $stmt->bind_param("i",$id_vendedor); 

    $stmt->execute();
    
    // Devolvemos el resultado para que el script de edición lo use
    return $stmt->get_result(); 
}


// En EmpleadoDAO.php

public function actualizarEmpleado($id, $nombre, $primerAp, $segundoAp, $salarioBase, $porcentajeComision) {
    $conn = $this->conexion->getConexion();
    
    // Consulta SQL: 5 SETs + 1 WHERE = 6 marcadores de posición
    $sql = "UPDATE Vendedor
          SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comisión = ?
          WHERE idVendedor = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error al preparar la consulta de actualización: " . $conn->error);
        return false;
    }

    // Vincular parámetros: 3 strings, 2 decimals/doubles, 1 integer (ID)
    $stmt->bind_param("sssddi", 
        $nombre, 
        $primerAp, 
        $segundoAp, 
        $salarioBase, // Dato Salario Base (double)
        $porcentajeComision, // Dato Porcentaje Comisión (double)
        $id // Dato ID Vendedor (integer)
    );

    $res = $stmt->execute();

    $stmt->close();
    
    return $res;
}

}
?>