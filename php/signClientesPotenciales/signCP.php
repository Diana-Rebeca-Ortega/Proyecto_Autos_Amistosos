<?php

// 1. Lógica PHP para manejar mensajes de estado (status)
$nombre_anterior = isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : '';
$apellido1_anterior = isset($_GET['apellido1']) ? htmlspecialchars($_GET['apellido1']) : '';
$email_anterior = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $mensaje = '';

    if ($status == 'campos_vacios') { 
       $mensaje = '🚨 Por favor, complete el Nombre, Primer Apellido y Email (campos requeridos).';
        $clase_alerta = 'alert-danger';
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
                
                <form action="../../php/signClientesPotenciales/procesar_registro_potencial.php" method="POST" >
<input type="hidden" name="accion" value="insertar">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                            autocomplete="given-name" 
                            value="<?php echo $nombre_anterior; ?>" required> 
                    </div>

                    <div class="mb-3">
                        <label for="apellido1" class="form-label">Primer Apellido</label>
                        <input type="text" class="form-control" id="apellido1" name="apellido1" 
                            autocomplete="family-name" 
                            value="<?php echo $apellido1_anterior; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellido2" class="form-label">Segundo Apellido (Opcional)</label>
                        <input type="text" class="form-control" id="apellido2" name="apellido2" 
                            autocomplete="additional-name">
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección (Opcional)</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" 
                            autocomplete="street-address">
                    </div>
                    
<div class="mb-3">
    <label for="email" class="form-label">Email de Contacto</label>
    <input type="email" class="form-control" id="email" name="email" 
        autocomplete="email"
        value="<?php echo $email_anterior; ?>" required>
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