<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Autos Amistosos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos básicos para centrar el formulario */
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php
// Captura las variables de la URL.
$usuario_anterior = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : '';
$perfil_anterior = isset($_GET['perfil']) ? htmlspecialchars($_GET['perfil']) : '';
?>

<?php
// ... (Bloque para capturar $usuario_anterior y $perfil_anterior es el mismo)

if (isset($_GET['error'])) {
    $error = $_GET['error'];
    $mensaje = '';

    if ($error == 'campos_vacios') {
        $mensaje = '🚨 Por favor, ingresa tu Usuario, Contraseña y selecciona tu Perfil.';
    } elseif ($error == 'usuario_invalido') {
        $mensaje = '❌ El **Usuario** ingresado no existe. Inténtalo de nuevo.';
    } elseif ($error == 'password_invalida') {
        $mensaje = '❌ **Contraseña** incorrecta. Por favor, verifica tu clave.';
    } elseif ($error == 'perfil_invalido') {
        $mensaje = '❌ El **Perfil** seleccionado no coincide con el Usuario/Contraseña.';
    } elseif ($error == 'db_error') {
        $mensaje = '⚠️ Error interno del sistema. Inténtalo más tarde.';
    }
    
    // Muestra el mensaje de alerta de Bootstrap
    if (!empty($mensaje)) {
        echo '<div class="alert alert-danger alert-dismissible fade show fixed-top m-3" role="alert" style="z-index: 1050;">';
        echo $mensaje;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}
?>
<div class="login-container">
    <h2 class="text-center mb-4">Iniciar Sesión</h2>
    
   <!-- <form action="../../php/login/validar_login.php" method="POST" novalidate>-->
    <form action="../../php/controllers/validar_usuario.php" method="POST" novalidate >
      <div class="mb-3">
    <label for="perfil" class="form-label">Selecciona tu Perfil</label>
    <select class="form-select" id="perfil" name="perfil" required>
        <option disabled value="" <?php echo empty($perfil_anterior) ? 'selected' : ''; ?>>Elegir...</option> 
        
        <option value="dueno" <?php echo ($perfil_anterior === 'dueno') ? 'selected' : ''; ?>>Dueño (Jim Amistoso)</option>
        
        <option value="administrador" <?php echo ($perfil_anterior === 'administrador') ? 'selected' : ''; ?>>Administrador</option>
        
        <option value="vendedor" <?php echo ($perfil_anterior === 'vendedor') ? 'selected' : ''; ?>>Vendedor</option>
    </select>
    <div class="invalid-feedback">
        Por favor, selecciona un perfil.
    </div>
</div>

      <div class="mb-3">
    <label for="usuario" class="form-label">Usuario o Email</label>
    <input type="text" class="form-control" id="usuario" name="usuario" 
           autocomplete="username" 
           value="<?php echo $usuario_anterior; ?>" > <div class="invalid-feedback">
        Ingresa tu usuario.
    </div>
</div>

        <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" 
                  autocomplete="current-password">
            <div class="invalid-feedback">
                Ingresa tu contraseña.
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary" id="entrar">Entrar al Sistema</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>