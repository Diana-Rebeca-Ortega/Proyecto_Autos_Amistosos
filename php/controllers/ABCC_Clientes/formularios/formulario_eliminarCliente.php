<?php
session_start();

if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
session_unset();
 session_destroy();
header("Location: ../../cerrar_sesion.php");
exit;
 }
include_once('../clienteDAO.php'); 

$cliente_obj = new clienteDAO();
// El método obtenerTodos() de la clase Cliente devuelve un ARRAY (PDO::FETCH_ASSOC)
$datos = $cliente_obj->obtenerTodos(); 

$contador = 1; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bajas y Cambios de Cliente</title> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.container { max-width: 900px; margin-top: 50px; }
</style>
</head>
<body>

<div class="container">
<h2 class="mb-4 text-primary">Eliminar Cliente 👤</h2>
</div>

<div class="container">
<?php
        // 1. Verificar si $datos es un array (que es lo que devuelve el DAO de PDO)
        // 2. Verificar que no haya fallado la conexión (si falló, devuelve [])

        if (is_array($datos)) {
            $num_filas = count($datos);
            
            if($num_filas === 0){
                echo "<div class='alert alert-info' role='alert'>No se encontraron registros de clientes.</div>";
            } else {
                // ---- COMIENZA LA TABLA ----
                echo '<table class="table table-striped table-hover">';
                echo '<thead>';
                echo '<tr>';
                echo '<th scope="col">#</th>';
               echo '<th scope="col">ID Cliente</th>';
               echo ' <th scope="col">Nombre</th>';
                echo '<th scope="col">Primer Ap.</th>';
                echo '<th scope="col">Teléfono</th>';
               echo '<th scope="col">Email</th>';
               echo '<th scope="col">ACCIONES</th>';
                echo ' </tr>';
                echo '</thead>';
                echo '<tbody>';

                // Iteramos sobre el array de resultados
                foreach($datos as $fila){ // <--- CAMBIO CRÍTICO: Usamos foreach en lugar de while/fetch_assoc
                    printf(
                        "<tr> 
                            <td> %s </td>
                            <td>%s</td> 
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td> 
                                <a href=\"../procesar_bajaCliente.php?accion=eliminar&id=%s\" class=\"btn btn-danger btn-sm\"
                                    onclick=\"return confirm('¿Está seguro de ELIMINAR al cliente %s?');\"> Eliminar </a>   
                            </td>
                        </tr>", 
                        
                        // ARGUMENTOS DE PRINFTF (8 en total)
                        $contador++, // 1. Contador
                        $fila['idCliente'], // 2. ID Cliente
                        $fila['Nombre'], // 3. Nombre
                        $fila['Apellido1'], // 4. Apellido1
                        $fila['Telefono'], // 5. Teléfono
                        $fila['Email'], // 6. Email
                        
                        $fila['idCliente'], // 7. ID para el enlace Eliminar
                        $fila['Nombre'] . ' ' . $fila['Apellido1'] // 8. Nombre para la alerta de confirmación
                    );
                }
                echo '</tbody>';
                echo '</table>';
            }
        } else {
            // Este else solo se ejecutaría si $datos no fuera un array, lo cual 
            // no debería ocurrir si el DAO devuelve [] en caso de fallo.
            echo "<div class='alert alert-danger' role='alert'>Error interno del sistema DAO (resultado inesperado).</div>";
        }
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>