<?php

// 1. Lógica PHP para manejar mensajes de estado (status)
$nombre_anterior = isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '';
$apellido1_anterior = isset($_GET['apellido1']) ? htmlspecialchars($_GET['apellido1']) : '';
$email_anterior = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

// ----------------------------------------------------
// 🛑 LÓGICA AGREGADA PARA CAPTURAR Y DECODIFICAR ERRORES
// ----------------------------------------------------
$errores = []; // Inicializamos el array de errores
if (isset($_GET['errores'])) {
    // Decodificamos el JSON de errores que viene en la URL
    $errores = json_decode(urldecode($_GET['errores']), true);
}


if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $mensaje = '';

    // Cambiamos 'campos_vacios' a 'errores_de_validacion' para ser más específicos
    if ($status == 'errores_de_validacion') { 
       // Se muestra un mensaje genérico, los detalles irán campo por campo
       $mensaje = '🚨 Por favor, revise los errores en los campos marcados.';
       $clase_alerta = 'alert-warning';
    } elseif ($status == 'registro_exitoso') { 
        $mensaje = '✅ ¡Registro completado! Un vendedor de Autos Amistosos se pondrá en contacto pronto.';
        $clase_alerta = 'alert-success';
    } elseif ($status == 'registro_fallido') { 
        $mensaje = '❌ Error al registrar sus datos. Inténtelo más tarde.';
        $clase_alerta = 'alert-danger';
    }
    
    // Muestra el mensaje de alerta de Bootstrap
    if (!empty($mensaje)) {
        echo '<div class="alert ' . $clase_alerta . ' alert-dismissible fade show fixed-top m-3" role="alert" style="z-index: 1050;">';
        echo $mensaje;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Interés - Autos Amistosos</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* 1. Estilos del Contenedor Principal (Pantalla Completa) */
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #e0f7fa;
        }
        .main-login-wrapper {
            display: flex; 
            height: 100vh; 
        }

        /* 2. Estilos del Panel de la Imagen (Izquierda) */
        .image-panel {
            flex: 1.5;
            background: url('../../images/autorosa.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
        }
        
        /* 3. Estilos del Panel del Formulario (Derecha) */
      .form-panel {
    flex: 1; 
    display: flex;
    justify-content: center;
    align-items: flex-start; 
    background-color: #e0f7fa;
    padding-top: 50px; 
}
        .login-container {
            width: 100%;
            max-width: 380px; 
            padding: 40px;
            background-color: white; 
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilo para el título grande */
        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1f3a5f; 
            margin-bottom: 25px;
            text-align: center;
        }
        
        /* Estilo para el botón */
        .btn-success {
            background-color: #28a745; /* Verde para 'Registrar' */
            border-color: #28a745;
        }

        /* 4. Media Queries para Móviles */
        @media (max-width: 992px) {
            .image-panel {
                display: none; 
            }
            .form-panel {
                width: 100%;
                flex: 1;
            }
        }
    </style>
</head>
<body>
    
    <div class="main-login-wrapper">
        
        <div class="image-panel">
        </div>

        <div class="form-panel">
            <div class="login-container">
                <h2 class="login-title">REGISTRO DE INTERÉS</h2>
                
              <form action="../../php/signClientesPotenciales/procesar_registro_potencial.php" method="POST" id="registroForm">
    <input type="hidden" name="accion" value="insertar">
    
   <div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" 
        class="form-control <?php echo isset($errores['nombre']) ? 'is-invalid' : ''; ?>" 
        id="nombre" name="nombre" 
        autocomplete="given-name" 
        value="<?php echo $nombre_anterior; ?>" 
        required 
        pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" 
        title="Solo se permiten letras, espacios y acentos."
        maxlength="50">
    
    <?php if (isset($errores['nombre'])): ?>
        <div class="invalid-feedback">
            <?php echo $errores['nombre']; ?>
        </div>
    <?php else: ?>
        <div class="invalid-feedback">
            El nombre es requerido y solo debe contener letras, espacios y acentos.
        </div>
    <?php endif; ?>
</div>

    <div class="mb-3">
        <label for="apellido1" class="form-label">Primer Apellido</label>
        <input type="text" class="form-control" id="apellido1" name="apellido1" 
            autocomplete="family-name" 
            value="<?php echo $apellido1_anterior; ?>" 
            required
            pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+"
            title="Solo se permiten letras, espacios y acentos."
            maxlength="50">
        <div class="invalid-feedback">
            El primer apellido es requerido y solo debe contener letras, espacios y acentos.
        </div>
    </div>

    <div class="mb-3">
        <label for="apellido2" class="form-label">Segundo Apellido (Opcional)</label>
        <input type="text" class="form-control" id="apellido2" name="apellido2" 
            autocomplete="additional-name"
            pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]*"
            title="Solo se permiten letras, espacios y acentos.">
        <div class="invalid-feedback">
            Solo se permiten letras, espacios y acentos.
        </div>
    </div>

    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección (Opcional)</label>
        <input type="text" class="form-control" id="direccion" name="direccion" 
            autocomplete="street-address"
            maxlength="255">
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email de Contacto</label>
        <input type="email" class="form-control" id="email" name="email" 
            autocomplete="email"
            value="<?php echo $email_anterior; ?>" 
            required
            maxlength="100">
        <div class="invalid-feedback">
            Por favor, ingrese un email válido.
        </div>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" name="password" 
            autocomplete="new-password"
            required minlength="6" maxlength="50">
        <div class="form-text">Mínimo 6 caracteres.</div>
        <div class="invalid-feedback" id="feedback-password">
            La contraseña es requerida y debe tener al menos 6 caracteres.
        </div>
    </div>
    
    <div class="mb-3">
        <label for="confirmar_password" class="form-label">Confirmar Contraseña</label>
        <input type="password" class="form-control" id="confirmar_password" name="confirmar_password" 
            autocomplete="new-password"
            required minlength="6" maxlength="50">
        <div class="invalid-feedback" id="feedback-confirmar">
            Las contraseñas no coinciden.
        </div>
    </div>
    
    <div class="mb-4">
        <label for="fuente" class="form-label">¿Cómo se enteró de nosotros?</label>
        <select class="form-select" id="fuente" name="fuente" required>
            <option value="">Seleccione una opción</option>
            <option value="WEB">Página Web</option>
            <option value="REFERIDO">Referido de Cliente</option>
            <option value="PUBLICIDAD">Publicidad / Redes Sociales</option>
            <option value="FERIA">Feria o Evento</option>
        </select>
        <div class="invalid-feedback">
            Debe seleccionar una opción.
        </div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-success btn-lg" id="registrar">Registrarme</button>
    </div>
</form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>