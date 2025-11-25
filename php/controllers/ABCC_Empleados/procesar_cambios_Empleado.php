<?php
// Incluye el DAO (Data Access Object) para interactuar con la DB
// Ajusta la ruta si es necesario
include_once('../empleado_dao.php');

// 1. OBTENER EL ID DEL EMPLEADO

// -----------------------------------------------------------------
if (!isset($_GET['idVendedor']) || empty($_GET['idVendedor'])) {
    die("Error: No se proporcionó el ID del empleado para editar.");
}

$idVendedor = $_GET['idVendedor'];
$empleadoDAO = new EmpleadoDAO();

// Aquí necesitarás un nuevo método en tu DAO: getEmpleadoByID()
$datos_empleado = $empleadoDAO->getEmpleadoByID($idVendedor); 

if (mysqli_num_rows($datos_empleado) == 0) {
    die("Error: Empleado no encontrado.");
}

$empleado = mysqli_fetch_assoc($datos_empleado);
$nombre = htmlspecialchars($empleado['Nombre']);
$apellido1 = htmlspecialchars($empleado['Apellido1']); 
$apellido2 = htmlspecialchars($empleado['Apellido2']);
$salario_base = htmlspecialchars($empleado['Salario_Base']);
$porcentaje_comision = htmlspecialchars($empleado['Porcentaje_Comisión']);


// 2. MOSTRAR EL FORMULARIO PRE-LLENADO
// -----------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Vendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 700px; margin-top: 50px; } /* Lo hacemos más ancho */
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-warning">Editar Vendedor ID: ...<?php echo $idVendedor ?? 'N/A'; ?></h2>

    <form class="row g-3" action="procesar_actualizacion_Empleado.php" method="POST">
        
       <input type="hidden" name="idVendedor" value="<?php echo $idVendedor ?? ''; ?>">
        
        <div class="col-md-6">
            <label for="caja_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="caja_nombre" name="nombre" 
                   value="<?php echo $nombre ?? ''; ?>" required maxlength="45">
        </div>

        <div class="col-md-6">
            <label for="caja_apellido1" class="form-label">Primer Apellido (Apellido1)</label>
            <input type="text" class="form-control" id="caja_apellido1" name="apellido1" 
                   value="<?php echo $apellido1 ?? ''; ?>" required maxlength="45">
        </div>

        <div class="col-md-6">
            <label for="caja_apellido2" class="form-label">Segundo Apellido (Apellido2)</label>
            <input type="text" class="form-control" id="caja_apellido2" name="apellido2" 
                   value="<?php echo $apellido2 ?? ''; ?>" maxlength="45">
        </div>

        <div class="col-md-6">
            <label for="caja_salario" class="form-label">Salario Base</label>
            <input type="number" class="form-control" id="caja_salario" name="salario_base" 
                   value="<?php echo $salario_base ?? ''; ?>"
                   required step="0.01" min="0" max="9999999.99">
        </div>
        
        <div class="col-md-6">
            <label for="caja_comision" class="form-label">Porcentaje Comisión</label>
            <input type="number" class="form-control" id="caja_comision" name="porcentaje_comision" 
                   value="<?php echo $porcentaje_comision ?? ''; ?>"
                   required step="0.0001" min="0" max="0.9999">
        </div>

        <div class="col-12 mt-4">
            <button class="btn btn-warning" type="submit">Guardar Cambios</button>
            <a href="../formulario_actualizarEmpleado.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>