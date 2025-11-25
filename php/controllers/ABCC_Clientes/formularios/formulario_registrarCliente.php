<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 600px; margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-primary">Registro de Nuevo Cliente 👤</h2>
    
    <form class="row g-3 needs-validation" 
    action= "../procesar_altasCliente.php" method="POST" novalidate> 
    
        <input type="hidden" name="accion" value="insertar"> 

        <div class="col-md-6">
            <label for="caja_nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="caja_nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($nombre ?? ''); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" maxlength="45">
            <div class="invalid-feedback">Ingrese un nombre válido (letras y espacios, máx 45).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_apellido1" class="form-label">Primer Apellido</label>
            <input type="text" class="form-control" id="caja_apellido1" name="apellido1" 
                   value="<?php echo htmlspecialchars($apellido1 ?? ''); ?>"
                   required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]+" maxlength="45">
            <div class="invalid-feedback">Ingrese el primer apellido (solo letras, máx 45).</div>
        </div>

        <div class="col-md-6">
            <label for="caja_apellido2" class="form-label">Segundo Apellido</label>
            <input type="text" class="form-control" id="caja_apellido2" name="apellido2" 
                   value="<?php echo htmlspecialchars($apellido2 ?? ''); ?>"
                   pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ]*" maxlength="45">
            <div class="invalid-feedback">Si lo ingresa, use solo letras (máx 45).</div>
        </div>
        
        <div class="col-md-6">
            <label for="caja_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="caja_email" name="email" 
                   value="<?php echo htmlspecialchars($email ?? ''); ?>"
                   maxlength="45">
            <div class="invalid-feedback">Ingrese un formato de email válido (máx 45).</div>
        </div>

        <div class="col-12">
            <label for="caja_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="caja_direccion" name="direccion" 
                   value="<?php echo htmlspecialchars($direccion ?? ''); ?>"
                   maxlength="200">
            <div class="invalid-feedback">Ingrese la dirección (máx 200).</div>
        </div>
        
        <div class="col-md-6">
            <label for="caja_telefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="caja_telefono" name="telefono" 
                   value="<?php echo htmlspecialchars($telefono ?? ''); ?>"
                   pattern="[0-9\s\+\-()]*" maxlength="15">
            <div class="invalid-feedback">Ingrese un teléfono válido (solo números, +, -, ()).</div>
        </div>
        
        
        <div class="col-12 mt-4">
            <button class="btn btn-success" type="submit">Guardar Cliente</button>
            <button class="btn btn-secondary" type="reset">Limpiar Campos</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// JavaScript para la validación de Bootstrap
(() => {
  'use strict'
  const form = document.querySelector('.needs-validation')
  // No necesitamos la lógica de PHP aquí a menos que estés manejando errores
  // de PHP en el mismo archivo.
  
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