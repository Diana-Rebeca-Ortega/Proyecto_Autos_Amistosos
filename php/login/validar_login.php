<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Capturar el valor del campo 'perfil'
    $perfil_seleccionado = $_POST['perfil'] ?? null;
    
    // 2. Definir las rutas de redirección
    $rutas = [
        'dueno'  => '/PROYECTO/dueno/principal',     
    'administrador' => '/PROYECTO/admin/principal',    
    'vendedor' => '/PROYECTO/vendedor/principal'
    ];
    
    // 3. Verificar si el perfil fue seleccionado y si existe una ruta definida
    if ($perfil_seleccionado && isset($rutas[$perfil_seleccionado])) {
        $pagina_destino = $rutas[$perfil_seleccionado];
        header("Location: " . $pagina_destino);
        exit(); // Detiene la ejecución del script después de la redirección
        
    } else {
      echo "<script>";
        echo "alert('Aún no has seleccionado un perfil');";
        echo "window.location.href = '/PROYECTO/login';";
        echo "</script>";
        exit();
    }
    
} else {
    // Si se accede al archivo PHP sin enviar el formulario (ej. escribiendo la URL)
    header("Location: ../../index.html");
    exit();
}


?>