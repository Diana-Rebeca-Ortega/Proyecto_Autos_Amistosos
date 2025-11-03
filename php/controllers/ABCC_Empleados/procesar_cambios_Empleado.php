<?php
// Incluye el DAO (Data Access Object) para interactuar con la DB
// Ajusta la ruta si es necesario
include_once('../empleado_dao.php');

// 1. OBTENER EL ID DEL EMPLEADO

// -----------------------------------------------------------------
if (!isset($_GET['ID_Empleado']) || empty($_GET['ID_Empleado'])) {
    die("Error: No se proporcionó el ID del empleado para editar.");
}

$id_empleado = $_GET['ID_Empleado'];
$empleadoDAO = new EmpleadoDAO();

// Aquí necesitarás un nuevo método en tu DAO: getEmpleadoByID()
$datos_empleado = $empleadoDAO->getEmpleadoByID($id_empleado); 

if (mysqli_num_rows($datos_empleado) == 0) {
    die("Error: Empleado no encontrado.");
}

// Extrae los datos del resultado (solo debe ser una fila)
$empleado = mysqli_fetch_assoc($datos_empleado);

// Asigna las variables para pre-llenar el formulario
$nombre = htmlspecialchars($empleado['Nombre']);
$primer_apellido = htmlspecialchars($empleado['Primer_Apellido']);
$segundo_apellido = htmlspecialchars($empleado['Segundo_Apellido']);
$id_puesto = htmlspecialchars($empleado['ID_Puesto']);


// 2. MOSTRAR EL FORMULARIO PRE-LLENADO
// -----------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-warning">Editar Empleado ID: ...<?php echo $id_empleado; ?></h2>

    <form class="row g-3" action="procesar_actualizacion_Empleado.php" method="POST">
        
        <input type="hidden" name="id_empleado" value="<?php echo $id_empleado; ?>">
        
        <div class="col-md-6">
            <label for="caja_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="caja_nombre" name="nombre" 
                   value="<?php echo $nombre; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="caja_primerAp" class="form-label">Primer Apellido</label>
            <input type="text" class="form-control" id="caja_primerAp" name="primer_apellido" 
                   value="<?php echo $primer_apellido; ?>" required>
        </div>

        <div class="col-md-6">
            <label for="caja_segundoAp" class="form-label">Segundo Apellido</label>
            <input type="text" class="form-control" id="caja_segundoAp" name="segundo_apellido" 
                   value="<?php echo $segundo_apellido; ?>">
        </div>

        <div class="col-md-6">
            <label for="select_puesto" class="form-label">Puesto de Trabajo</label>
            <select class="form-select" id="select_puesto" name="id_puesto" required>
                <option value="1" <?php if ($id_puesto == 1) echo 'selected'; ?>>1 - Dueño</option>
                <option value="2" <?php if ($id_puesto == 2) echo 'selected'; ?>>2 - Administrador</option>
                <option value="3" <?php if ($id_puesto == 3) echo 'selected'; ?>>3 - Vendedor</option>
            </select>
        </div>
        
        <div class="col-12 mt-4">
            <button class="btn btn-warning" type="submit">Guardar Cambios</button>
            <a href="../../formulario_dar_baja_empleado.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>