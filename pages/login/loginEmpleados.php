<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Empleados - Autos Amistosos</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* 1. Estilos del Contenedor Principal (Pantalla Completa) */
      
.captcha-container {
    display: flex; /* Permite alinear ítems horizontalmente */
    align-items: center; /* ESTO ES LO CLAVE: Alinea verticalmente los hijos (imagen y input) */
    gap: 10px; /* Espacio entre la imagen y el campo de texto */
    margin-bottom: 20px;
}
.captcha-container img {
    /* Establece la altura definida de la imagen */
    height: 40px; 
    border: 1px solid #ccc;
    border-radius: 4px;
}
.captcha-input-group {
    /* Asegura que el campo de texto ocupe el espacio restante */
    flex-grow: 1; 
}
/* Asegura que el input dentro del grupo tenga la misma altura y estilo que los otros inputs */
.captcha-input-group input {
    height: 38px; /* Ajusta la altura si es necesario para coincidir con los demás inputs de Bootstrap */
}
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
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
            align-items: center;
            background-color: #e0f7fa;
        }
        .login-container {
            width: 100%;
            max-width: 380px;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        /* Estilo para el título grande, similar al "Iniciar Sesión" */
        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1f3a5f;
            margin-bottom: 25px;
            text-align: center;
        }
        
        /* Estilo para el botón */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
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
        /* 💡 CORRECCIÓN 1: ESTILOS DEL CAPTCHA AÑADIDOS */
        .captcha-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px; /* Separación del botón */
            gap: 10px; 
        }
        .captcha-container img {
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .captcha-input-group {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <?php
    // 💡 CORRECCIÓN 2: INICIAR SESIÓN OBLIGATORIO
    session_start();

    $usuario_anterior = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : '';

    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $mensaje = '';
        
        if ($error == 'campos_vacios') { 
            $mensaje = '🚨 Por favor, asegurate de ingresar tu Usuario, Contraseña y validar el Catpcha .';
        } elseif ($error == 'db_error') { 
            $mensaje = '⚠️ Error de conexión. Inténtalo más tarde.';
        } elseif ($error == 'credenciales_invalidas') { 
            $mensaje = '❌ Usuario o Contraseña incorrectos. Verifica tus datos.';
        } elseif ($error == 'captcha_invalido') { // 💡 CORRECCIÓN 3: MANEJO DEL NUEVO ERROR
            $mensaje = '🚫 La verificación de seguridad es incorrecta. Inténtalo de nuevo.';
        } else {
            $mensaje = '⚠️ Error desconocido. Inténtalo de nuevo.';
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

    <div class="main-login-wrapper">
        
        <div class="image-panel">
        </div>

        <div class="form-panel">
            <div class="login-container">
                <h2 class="login-title">INICIAR SESIÓN</h2>
                
                <form action="../../php/controllers/validar_usuario.php" method="POST" novalidate>

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario o Email</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" 
                               autocomplete="username" 
                               value="<?php echo $usuario_anterior; ?>" > 
                        <div class="invalid-feedback">Ingresa tu usuario.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               autocomplete="current-password">
                        <div class="invalid-feedback">Ingresa tu contraseña.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label d-block">Verificación de Seguridad</label>
                        <div class="captcha-container">
                            
                            <img src="generar_captcha.php" alt="Código de verificación" width="120" height="40">
                            
                            <div class="captcha-input-group">
                                <input type="text" class="form-control" id="captcha_input" name="captcha_input" 
                                       placeholder="Escribe el número" required style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="entrar">Entrar al Sistema</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>