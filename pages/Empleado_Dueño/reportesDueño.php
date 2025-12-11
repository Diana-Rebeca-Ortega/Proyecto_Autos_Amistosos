<?php
session_start();

// 1. Lógica de Seguridad
if (!isset($_SESSION['usuario_autenticado']) || $_SESSION['usuario_autenticado'] !== true) {
    session_unset();
    session_destroy();
    header("Location: ../../cerrar_sesion.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes del Dueño - Autos Amistosos</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; margin-top: 50px; }
        .card { transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-5 text-dark text-center">Dashboard de Reportes para Jim Amistoso 📈</h2>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        
        <div class="col">
            <div class="card h-100 border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">🏆 Desempeño de Vendedores</h5>
                    <p class="card-text">
                        Genera un reporte detallado de ventas y **comisiones** ganadas por cada vendedor en un periodo específico (Utiliza el **Procedimiento Almacenado**).
                    </p>
                    <a href="./formulario_reporteDesempenoVendedor.php" class="btn btn-success stretched-link">Ver Reporte</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">🚗 Inventario Actual</h5>
                    <p class="card-text">
                        Lista de todos los vehículos nuevos en stock, incluyendo fecha de entrega y precio de lista.
                    </p>
                    <a href="#" class="btn btn-primary stretched-link disabled">Ver Reporte (Pendiente)</a>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card h-100 border-info">
                <div class="card-body">
                    <h5 class="card-title text-info">⭐ Satisfacción del Cliente</h5>
                    <p class="card-text">
                        Resumen de las encuestas enviadas un mes después de la venta.
                    </p>
                    <a href="#" class="btn btn-info stretched-link disabled">Ver Encuestas (Pendiente)</a>
                </div>
            </div>
        </div>
        
    </div>

    <div class="text-center mt-5">
        <a href="./menuPrincipal_ED.php" class="btn btn-secondary">Volver al Menú Principal</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>