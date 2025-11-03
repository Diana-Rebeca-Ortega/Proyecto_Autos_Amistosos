<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empleado - Formulario Auto-Procesado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-primary">Registro de Nuevo Empleado 🧑‍💼</h2>
    
    

    <form class="row g-3 needs-validation" 
    action= "./controllers/ABCC_Empleados/procesar_altas_Empleado.php" method="POST" novalidate>

        <div class="col-md-6">
            <label for="caja_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="caja_nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($nombre ?? 'Juan'); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" maxlength="50">
            <div class="invalid-feedback">Ingrese un nombre válido (letras y espacios, máx 50).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_primerAp" class="form-label">Primer Apellido</label>
            <input type="text" class="form-control" id="caja_primerAp" name="primer_apellido" 
                   value="<?php echo htmlspecialchars($primer_apellido ?? 'Ortega'); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+" maxlength="50">
            <div class="invalid-feedback">Ingrese el primer apellido (solo letras, máx 50).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_segundoAp" class="form-label">Segundo Apellido</label>
            <input type="text" class="form-control" id="caja_segundoAp" name="segundo_apellido" 
                   value="<?php echo htmlspecialchars($segundo_apellido ?? ''); ?>"
                   pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]*" maxlength="50">
            <div class="invalid-feedback">Si lo ingresa, use solo letras (máx 50).</div>
        </div>

        <div class="col-md-6">
            <label for="select_puesto" class="form-label">Puesto de Trabajo</label>
            <select class="form-select" id="select_puesto" name="id_puesto" required>
                <option disabled value="" <?php echo (!isset($id_puesto) || $id_puesto === 'Gerente') ? 'selected' : ''; ?>>Seleccionar...</option>
                <option value="1" <?php echo (isset($id_puesto) && $id_puesto == 1) ? 'selected' : ''; ?>>1 - Gerente</option>
                <option value="2" <?php echo (isset($id_puesto) && $id_puesto == 2) ? 'selected' : ''; ?>>2 - Vendedor</option>
                <option value="3" <?php echo (isset($id_puesto) && $id_puesto == 3) ? 'selected' : ''; ?>>3 - Administrador</option>
            </select>
            <div class="invalid-feedback">Por favor, selecciona el puesto.</div>
        </div>
        
        <div class="col-12 mt-4">
            <button class="btn btn-success" type="submit">Registrar Empleado</button>
            <button class="btn btn-secondary" type="reset">Limpiar Campos</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
