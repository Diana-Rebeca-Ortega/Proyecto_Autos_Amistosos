<?php
echo "Validando";

// =========================================================
// Archivo: validar_login.php
// Función: Redireccionar al panel según el perfil seleccionado
// =========================================================

// Siempre verifica que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Capturar el valor del campo 'perfil'
    $perfil_seleccionado = $_POST['perfil'] ?? null;
    
    // 2. Definir las rutas de redirección
    $rutas = [
        'dueno'         => '../../pages/Empleado_Dueño/menuPrincipal_ED.html',
        'administrador' => '../../pages/Empleado_Administrador/menuPrincipal_EA.html',
        'vendedor'      => '../../pages/Empleado_Vendedor/menuPrincipal_EV.html'
    ];
    
    // 3. Verificar si el perfil fue seleccionado y si existe una ruta definida
    if ($perfil_seleccionado && isset($rutas[$perfil_seleccionado])) {
        
        // ** (Aquí iría la LÓGICA DE VALIDACIÓN REAL con la base de datos) **
        // Por ahora, asumimos que el login es exitoso si se seleccionó un perfil válido
        
        $pagina_destino = $rutas[$perfil_seleccionado];
        
        // Realizar la redirección
        header("Location: " . $pagina_destino);
        exit(); // Detiene la ejecución del script después de la redirección
        
    } else {
      echo "<script>";
        echo "alert('Aún no has seleccionado un perfil');";
        // Opcional: Redirigir de vuelta al formulario después de la alerta
        echo "window.location.href = '../../pages/login/loginEmpleados.html';";
        echo "</script>";
        exit();
    }
    
} else {
    // Si se accede al archivo PHP sin enviar el formulario (ej. escribiendo la URL)
    header("Location: ../../index.html");
    exit();
}


?>