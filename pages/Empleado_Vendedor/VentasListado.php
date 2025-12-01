<?php
// Incluimos el DAO que contiene la lógica de la base de datos
include_once(__DIR__.'/../../php/controllers/ABCC_Ventas/VistaVentasDAO.php');

// 1. OBTENER DATOS
$ventasDAO = new VistaVentasDAO();
$listado_ventas = $ventasDAO->listarVentas();
$ventasDAO->cerrarConexion();

$error_consulta = ($listado_ventas === false);
$sin_resultados = (is_array($listado_ventas) && empty($listado_ventas));

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Ventas Detalladas - Autos Amistosos</title>
    <!-- Incluir Tailwind CSS (usamos CDN para simplificar) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
        .table-header { background-color: #1f3a93; color: white; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-7xl mx-auto bg-white p-6 md:p-10 shadow-xl rounded-xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-6 border-b pb-2">
            Detalle de Ventas
        </h1>
        <p class="text-gray-600 mb-8">
            Este listado se genera a partir de la **VIEW `VistaVenta`** (consulta multitabla).
        </p>

        <?php if ($error_consulta): ?>
            <!-- Mostrar mensaje de error si la consulta falló -->
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error en la Base de Datos:</strong>
                <span class="block sm:inline">No se pudo obtener el listado de ventas. Revise el log de errores.</span>
            </div>
        <?php elseif ($sin_resultados): ?>
            <!-- Mostrar mensaje si no hay ventas registradas -->
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Información:</strong>
                <span class="block sm:inline">No se encontraron ventas en el sistema.</span>
            </div>
        <?php else: ?>
            <!-- MOSTRAR LA TABLA DE RESULTADOS -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 shadow-md rounded-lg">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">ID Venta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Vendedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Automóvil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Kilometraje</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider rounded-tr-lg">Precio Final</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($listado_ventas as $venta): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($venta['idVenta']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars(date('d/m/Y', strtotime($venta['Fecha_Venta']))) ?></td>
                               <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    <?= htmlspecialchars($venta['Nombre_Vendedor']) ?>
</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    <?= htmlspecialchars($venta['Modelo_Automovil']) ?> 
    (VIN: <?= htmlspecialchars($venta['idAutomovil']) ?>)
</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= number_format($venta['Kilometraje_Entrega']) ?> km
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    $<?= number_format($venta['Precio_Final'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>