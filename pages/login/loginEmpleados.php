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
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .main-login-wrapper {
            display: flex; /* Habilita el diseño de dos columnas */
            height: 100vh; /* Ocupa el 100% de la altura de la vista */
        }

        /* 2. Estilos del Panel de la Imagen (Izquierda) */
        .image-panel {
            flex: 1.5; /* Ocupa más espacio que el panel del formulario (ej. 60%) */
            background: url('../../images/autorosa.jpg') no-repeat center center;
            /* **IMPORTANTE:** Cambia 'https://via.placeholder/...' por la URL o ruta local de tu propia imagen. */
            background-size: cover;
            position: relative;
            display: flex;
            
        }
        
        /* 3. Estilos del Panel del Formulario (Derecha) */
        .form-panel {
            flex: 1; /* Ocupa menos espacio que el panel de la imagen (ej. 40%) */
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e0f7fa; /* Un color suave para el fondo del formulario, similar al ejemplo */
        }
        .login-container {
            width: 100%;
            max-width: 380px; /* Ancho máximo para el formulario */
            padding: 40px;
            background-color: white; /* El formulario en sí tiene fondo blanco */
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Estilo para el título grande, similar al "Iniciar Sesión" */
        .login-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1f3a5f; /* Un color oscuro para el título */
            margin-bottom: 25px;
            text-align: center;
        }
        
        /* Estilo para el botón */
        .btn-primary {
            background-color: #007bff; /* Color azul, puedes usar el color de tu marca */
            border-color: #007bff;
        }

        /* 4. Media Queries para Móviles */
        @media (max-width: 992px) {
            .image-panel {
                display: none; /* Oculta la imagen en dispositivos pequeños */
            }
            .form-panel {
                width: 100%;
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <?php
    // ... (Tu lógica PHP para capturar variables y errores)
    $usuario_anterior = isset($_GET['usuario']) ? htmlspecialchars($_GET['usuario']) : '';

    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        $mensaje = '';

        // ... (lógica PHP para los mensajes de error)
       if ($error == 'campos_vacios') { 
            $mensaje = '🚨 Por favor, ingresa tu Usuario y Contraseña.';
        } elseif ($error == 'db_error') { 
            $mensaje = '⚠️ Error de conexión. Inténtalo más tarde.';
        } elseif ($error == 'credenciales_invalidas') { 
            // NUEVO: Mensaje genérico, seguro y consistente.
            $mensaje = '❌ Usuario o Contraseña incorrectos. Verifica tus datos.';
        } else {
            // Manejo de cualquier otro error inesperado
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