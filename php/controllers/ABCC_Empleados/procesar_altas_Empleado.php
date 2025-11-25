<?php
include_once('../empleado_dao.php'); 

// 1. Inicializa el objeto DAO
// 📢 CAMBIO 2: Instanciar la nueva clase
$vendedorDAO = new EmpleadoDAO();
echo "<h1>PROCESAMIENTO DE ALTAS DE VENDEDORES</h1>";


// 2. Captura los datos enviados por el formulario (POST)
$nombre              = $_POST['nombre'] ?? '';
$apellido1           = $_POST['apellido1'] ?? '';
$apellido2           = $_POST['apellido2'] ?? '';
$salario_base        = $_POST['salario_base'] ?? 0.00; // Usar 0.00 como fallback numérico
$porcentaje_comision = $_POST['porcentaje_comision'] ?? 0.0000; // Usar 0.0000 como fallback numérico

// Líneas de depuración actualizadas
echo "Nombre: " . htmlspecialchars($nombre) . "<br>";
echo "Apellido 1: " . htmlspecialchars($apellido1) . "<br>";
echo "Apellido 2: " . htmlspecialchars($apellido2) . "<br>";
echo "Salario Base: " . htmlspecialchars($salario_base) . "<br>";
echo "Comisión: " . htmlspecialchars($porcentaje_comision) . "<br>";


// NOTA: La variable $datos_correctos debería provenir de una VALIDACIÓN REAL.
// La mantenemos por estructura, pero la validación de negocio (ej. Salario > 0)
// debería ir aquí.
$datos_correctos = true;

if($datos_correctos){
   
    $res = $vendedorDAO->agregarEmpleado(
        $nombre, 
        $apellido1, 
        $apellido2, 
        $salario_base, 
        $porcentaje_comision
    );
    
    if($res){
        echo "<h2 style='color: green;'>✅ ¡Vendedor registrado con éxito en la Base de Datos!</h2>";
    }
    else {
        echo "<h2 style='color: red;'>❌ Error: No se pudo registrar al vendedor.</h2>";
        echo "<p>Verifique que el DAO esté apuntando a la tabla 'Vendedor' correctamente.</p>";
    }
}
else{
    echo "<h2>Error en la validación de datos.</h2>";
}
?>