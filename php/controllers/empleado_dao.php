<?php
require_once(__DIR__ . '/../database/conexion_bdd_autos_amistosos.php');
class EmpleadoDAO{
    private $conexion;

    public function __construct(){
       $this->conexion = ConexionBDautosAmistosos::getInstancia();
    }
    //METODOS ABCC (CRUD)
    //*****************ALTAS***************** */
 public function agregarEmpleado($nombre, $apellido1, $apellido2, $salario_base, $porcentaje_comision) { 
    $conn = $this->conexion->getConexion(); 
    $sql = "INSERT INTO Vendedor (Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([
        $nombre, 
        $apellido1, 
        $apellido2, 
        $salario_base, 
        $porcentaje_comision
    ]);
    return $res;
}
public function mostrarEmpleado($filtro){
    $conn = $this->conexion->getConexion();
    $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión
            FROM Vendedor";            
    $stmt = $conn->query($sql);
    return $stmt; 
}
public function buscarVendedores($termino_busqueda) {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión
            FROM Vendedor 
            WHERE Nombre LIKE ? OR Apellido1 LIKE ? OR Apellido2 LIKE ?";
    $param_like = "%" . $termino_busqueda . "%";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$param_like, $param_like, $param_like]);    
    return $stmt; 
}

public function eliminarEmpleado($id_vendedor){    
    $conn = $this->conexion->getConexion();
    $sql = "DELETE FROM Vendedor WHERE idVendedor = ?";
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([$id_vendedor]);
    return $res; 
}

public function getEmpleadoByID($id_vendedor) {
    $conn = $this->conexion->getConexion();
    $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión 
            FROM Vendedor WHERE idVendedor = ?";    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_vendedor]);
    return $stmt; 
}
public function actualizarEmpleado($id, $nombre, $primerAp, $segundoAp, $salarioBase, $porcentajeComision) {
    $conn = $this->conexion->getConexion();
    $sql = "UPDATE Vendedor
            SET Nombre = ?, Apellido1 = ?, Apellido2 = ?, Salario_Base = ?, Porcentaje_Comisión = ?
            WHERE idVendedor = ?";    
    $stmt = $conn->prepare($sql);
    $res = $stmt->execute([
        $nombre, 
        $primerAp, 
        $segundoAp, 
        $salarioBase, 
        $porcentajeComision, 
        $id 
    ]);
    return $res;
}

public function obtenerTodos(){
    $conn = $this->conexion->getConexion();
    $sql = "SELECT idVendedor, Nombre, Apellido1, Apellido2, Salario_Base, Porcentaje_Comisión FROM Vendedor";
    $res = $conn->query($sql);
    return $res;
}
}
?>