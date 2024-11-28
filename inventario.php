<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'conexion.php';

// Obtener motos
$motos = $pdo->query("SELECT * FROM motos")->fetchAll(PDO::FETCH_ASSOC);

// Obtener refacciones
$refacciones = $pdo->query("SELECT * FROM refacciones")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario General</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h2>Inventario de Motos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Propietario</th>
            <th>Precio</th>
        </tr>
        <?php foreach ($motos as $moto): ?>
            <tr>
                <td><?php echo $moto['id']; ?></td>
                <td><?php echo $moto['marca']; ?></td>
                <td><?php echo $moto['modelo']; ?></td>
                <td><?php echo $moto['anio']; ?></td>
                <td><?php echo $moto['propietario']; ?></td>
                <td><?php echo $moto['precio']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Inventario de Refacciones</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Precio</th>
        </tr>
        <?php foreach ($refacciones as $refaccion): ?>
            <tr>
                <td><?php echo $refaccion['id']; ?></td>
                <td><?php echo $refaccion['nombre']; ?></td>
                <td><?php echo $refaccion['tipo']; ?></td>
                <td><?php echo $refaccion['cantidad']; ?></td>
                <td><?php echo $refaccion['precio']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>

