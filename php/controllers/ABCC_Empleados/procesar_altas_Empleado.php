<?php
// Asegúrate de que la ruta a tu archivo DAO sea correcta.
include_once('../empleado_dao.php'); 

// 1. Inicializa el objeto DAO
$empleadoDAO = new EmpleadoDAO();
echo "<h1>PROCESAMIENTO DE ALTAS DE EMPLEADOS</h1>";
echo htmlspecialchars($_POST['nombre']);
echo htmlspecialchars($_POST['primer_apellido']);
echo htmlspecialchars($_POST['segundo_apellido']);
echo htmlspecialchars($_POST['id_puesto']);


// 2. Captura los datos enviados por el formulario (POST)
// Usamos los nombres de los campos de tu formulario de Empleados: 'nombre', 'primer_apellido', 'segundo_apellido', 'id_puesto'.
$nombre = $_POST['nombre'] ?? '';
$primer_apellido = $_POST['primer_apellido'] ?? '';
$segundo_apellido = $_POST['segundo_apellido'] ?? '';
$id_puesto = $_POST['id_puesto'] ?? '';

// var_dump($nombre); // Líneas de depuración opcionales
// var_dump($id_puesto);

// NOTA: La variable $datos_correctos debería provenir de una VALIDACIÓN REAL.
// Por ahora, asumiremos que si llegamos aquí, los datos son válidos o se gestionará
// la validación real en otra parte (idealmente antes de llamar al DAO).
$datos_correctos = true;

if($datos_correctos){
    // 3. Llama al método de inserción del DAO
    // El método debe recibir los atributos del nuevo empleado.
    $res = $empleadoDAO->agregarEmpleado($nombre, $primer_apellido, $segundo_apellido, $id_puesto);
    
    if($res){
        echo "<h2 style='color: green;'>✅ ¡Empleado registrado con éxito en la Base de Datos!</h2>";
        // Si tienes una página de confirmación, usa una redirección:
        // header('Location: ../pages/confirmacion_alta.php');
    }
    else {
        echo "<h2 style='color: red;'>❌ Error: No se pudo registrar al empleado.</h2>";
        echo "<p>Puede que el ID de Puesto no exista o haya un error de conexión.</p>";
    }
}
else{
    echo "<h2>Error en la validación de datos.</h2>";
}
?>