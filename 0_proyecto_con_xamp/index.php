<?php
define('ROOT_PATH', __DIR__ . '/');
$url_completa = isset($_GET['url']) ? $_GET['url'] : 'home';

$url_segmentos = explode('/', rtrim($url_completa, '/'));
$pagina = $url_segmentos[0]; 
$subpagina = $url_segmentos[1] ?? '';
$parametro = $url_segmentos[2] ?? '';

// 2. LÓGICA DE ENRUTAMIENTO (ROUTER)
$vista_path = '';

switch ($pagina) {
    case 'home':
        // Carga la página de inicio. Ahora busca home.html DENTRO de la carpeta pages.
        $vista_path = 'pages/home.html'; 
        break;
        
    case 'login':
        // Carga la página de login (que está en pages/login/loginEmpleados.php)
        $vista_path = 'pages/login/loginEmpleados.php'; 
        break;
        
    case 'signClientesPotenciales':
        // Carga la página de registro de clientes
        $vista_path = 'php/signClientesPotenciales/signCP.php'; 
        break;
        
   // CASO NUEVO: DUEÑO
    case 'dueño': 
        if ($subpagina === 'principal') {
            // Carga la vista real del Dueño (URL: /dueño/principal)
            $vista_path = 'pages/Empleado_Dueño/menuPrincipal_ED.php'; 
        } else {
            // Si es /dueño/ pero no 'principal' (ej: /dueño/otraCosa), va al 404
            goto default_404;
        }
        break; // Sale del switch ($pagina)

    // CASO NUEVO: ADMIN
    case 'admin':
        if ($subpagina === 'principal') {
            // Carga la vista real del Administrador (URL: /admin/principal)
            $vista_path = 'pages/Empleado_Administrador/menuPrincipal_EA.html';
        } else {
            goto default_404;
        }
        break;

    // CASO NUEVO: VENDEDOR
    case 'vendedor':
        if ($subpagina === 'principal') {
            // Carga la vista real del Vendedor (URL: /vendedor/principal)
            $vista_path = 'pages/Empleado_Vendedor/menuPrincipal_EV.php';
        } else {
            goto default_404;
        }
        break;
//ABCC EMPLEADOS******************************************************************************
case 'empleados': // 👈 **NUEVO: Solo matchea el primer segmento**
    switch ($subpagina) {
        // Carga de Formularios de ABCC
        case 'registrar': 
            $vista_path = 'php/formulario_registrarEmpleado.php'; 
            break;
        case 'eliminar':
            $vista_path = 'php/formulario_dar_baja_empleado.php'; 
            break;
        case 'actualizar':
            $vista_path = 'php/formulario_actualizarEmpleado.php'; 
            break;
        case 'buscar':
            $vista_path = 'php/formulario_consultasEmpleado.php'; 
            break;
        
        // Carga del Formulario de Edición (con ID)
        case 'editar': // URL: /empleados/editar/3
            $vista_path = 'php/controllers/ABCC_Empleados/procesar_cambios_Empleado.php'; // Tu formulario de edición
            // Pasamos el ID del parámetro a GET para que el script lo capture (necesario para el formulario de edición)
            $_GET['idVendedor'] = $parametro; 
            break;

        // Rutas de Procesamiento
        case 'procesar': // URL: /empleados/procesar/alta
            switch ($parametro) {
                case 'alta': 
                    $vista_path = 'php/controllers/ABCC_Empleados/procesar_altas_Empleado.php'; 
                    break;
                case 'baja': 
                    $vista_path = 'php/controllers/ABCC_Empleados/procesar_baja_Empleado.php'; 
                    break;
                case 'cambio': // Nueva ruta para el procesamiento de actualización
                    $vista_path = 'php/controllers/ABCC_Empleados/procesar_actualizacion_Empleado.php'; 
                    break;
                default:
                    // Si es /empleados/procesar/ pero no un parámetro válido, va al 404
                    goto default_404; 
            }
            break; // Salir del switch 'procesar'
        
        default:
            // Si es /empleados/ pero no una subpágina válida (ej: /empleados/x), va al 404
            goto default_404;
    }
    break;
//*********************************************************************************************     
  // ... Después de los casos de Empleados

// ABCC PROVEEDORES **********************************************************
case 'proveedores/registrar':
    // Asumimos que lo crearás en la carpeta php/
    $vista_path = 'php/formulario_registrar_proveedor.php'; 
    break;

case 'proveedores/eliminar':
    $vista_path = 'php/formulario_dar_baja_proveedor.php'; 
    break;

case 'proveedores/actualizar':
    $vista_path = 'php/formulario_actualizarProveedor.php'; 
    break;

case 'proveedores/buscar':
    $vista_path = 'php/formulario_consultasProveedor.php'; 
    break;

// INVENTARIO ****************************************************************
case 'inventario/estado':
    // Asumimos que lo crearás en la carpeta pages/Empleado_Dueño/
    $vista_path = 'pages/Empleado_Dueño/verificarEstadoInventario.php'; 
    break;
    
// *************************************************************************** 
// ... (Despues de INVENTARIO)

// MÓDULOS DEL NAVBAR *******************************************************
case 'finanzas/reportes':
    $vista_path = 'pages/reportes/finanzas.php';
    break;

case 'desempeño/reportes':
    $vista_path = 'pages/reportes/desempeño.php';
    break;

case 'configuracion':
    $vista_path = 'pages/configuracion.php';
    break;
    
case 'analisis':
    $vista_path = 'pages/analisis.php';
    break;
// *************************************************************************** default:
// CONTROL DE SESIÓN *********************************************************
case 'cerrar_sesion':   
    $vista_path = 'php/controllers/cerrar_sesion.php'; 
    break;
//**************************************************************** */
default_404:
default:
        // Maneja el error 404 para cualquier otra URL
        header("HTTP/1.0 404 Not Found");
        $vista_path = 'pages/404.html';
        break;
}
// Verificamos si la ruta de la vista existe antes de cargarla
if (file_exists($vista_path)) {   
    require $vista_path;
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Error 404</h1><p>No se encontró la vista777 para la página: **" . htmlspecialchars($pagina) . "**</p>";
}
?>