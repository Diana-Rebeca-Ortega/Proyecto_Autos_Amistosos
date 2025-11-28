<?php
session_start();

  if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ./controllers/cerrar_sesion.php");
    exit;
  }
  ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vendedor - Formulario Auto-Procesado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 700px; margin-top: 50px; } 
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-success">Registro de Nuevo Vendedor 💰</h2>
    
    <form class="row g-3 needs-validation" 
    action= "./controllers/ABCC_Empleados/procesar_altas_Empleado.php" method="POST" novalidate>

        <div class="col-md-6">
            <label for="caja_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="caja_nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($nombre ?? 'Juan'); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" maxlength="45"> <div class="invalid-feedback">Ingrese un nombre válido (letras y espacios, máx 45).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_apellido1" class="form-label">Primer Apellido (Apellido1)</label>
            <input type="text" class="form-control" id="caja_apellido1" name="apellido1" 
                   value="<?php echo htmlspecialchars($apellido1 ?? 'Ortega'); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+" maxlength="45"> <div class="invalid-feedback">Ingrese el primer apellido (solo letras, máx 45).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_apellido2" class="form-label">Segundo Apellido (Apellido2)</label>
            <input type="text" class="form-control" id="caja_apellido2" name="apellido2" 
                   value="<?php echo htmlspecialchars($apellido2 ?? ''); ?>"
                   pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]*" maxlength="45"> <div class="invalid-feedback">Si lo ingresa, use solo letras (máx 45).</div>
        </div>
        
        <div class="col-md-6">
            <label for="caja_salario" class="form-label">Salario Base (DECIMAL 9,2)</label>
            <input type="number" class="form-control" id="caja_salario" name="salario_base" 
                   value="<?php echo htmlspecialchars($salario_base ?? '15000.00'); ?>"
                   required step="0.01" min="0" max="9999999.99">
            <div class="invalid-feedback">Ingrese un salario base válido (ej. 15000.00).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_comision" class="form-label">Porcentaje Comisión (DECIMAL 5,4)</label>
            <input type="number" class="form-control" id="caja_comision" name="porcentaje_comision" 
                   value="<?php echo htmlspecialchars($porcentaje_comision ?? '0.0150'); ?>"
                   required step="0.0001" min="0" max="0.9999">
            <div class="invalid-feedback">Ingrese un porcentaje de comisión válido (ej. 0.0150 para 1.5%).</div>
        </div>
        
        <div class="col-12 mt-4">
            <button class="btn btn-success" type="submit">Registrar Vendedor</button>
            <button class="btn btn-secondary" type="reset">Limpiar Campos</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// El script de validación de Bootstrap puede quedar igual
(() => {
    'use strict'
    const form = document.querySelector('.needs-validation')
    // Solo activa la validación visual si la página fue cargada por un POST (envío fallido)
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && count($errores) > 0) : ?>
        form.classList.add('was-validated');
    <?php endif; ?>
    
    form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
        }
        form.classList.add('was-validated')
    }, false)
})()
</script>

</body>
</html>