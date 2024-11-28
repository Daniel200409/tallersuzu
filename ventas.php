<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'conexion.php';

// Obtener motos no vendidas
$motosDisponibles = $pdo->query("SELECT * FROM motos WHERE id NOT IN (SELECT id_moto FROM ventas)")->fetchAll(PDO::FETCH_ASSOC);

// Registrar una venta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registrar_venta'])) {
        $id_moto = $_POST['id_moto'];
        $comprador = $_POST['comprador'];
        $precio_venta = $_POST['precio_venta'];
        $fecha_venta = date('Y-m-d');

        $stmt = $pdo->prepare("INSERT INTO ventas (id_moto, comprador, fecha_venta, precio_venta) 
                               VALUES (:id_moto, :comprador, :fecha_venta, :precio_venta)");
        $stmt->execute([
            'id_moto' => $id_moto,
            'comprador' => $comprador,
            'fecha_venta' => $fecha_venta,
            'precio_venta' => $precio_venta
        ]);

        // Opcional: Eliminar moto del inventario si ya fue vendida
        // $pdo->prepare("DELETE FROM motos WHERE id = :id")->execute(['id' => $id_moto]);

        header("Location: ventas.php");
        exit();
    } elseif (isset($_POST['eliminar_venta'])) {
        // Eliminar venta
        $id_venta = $_POST['id_venta'];
        $pdo->prepare("DELETE FROM ventas WHERE id = :id")->execute(['id' => $id_venta]);
        header("Location: ventas.php");
        exit();
    } elseif (isset($_POST['editar_venta'])) {
        // Editar venta
        $id_venta = $_POST['id_venta'];
        $nuevo_comprador = $_POST['nuevo_comprador'];
        $nuevo_precio = $_POST['nuevo_precio'];

        $stmt = $pdo->prepare("UPDATE ventas SET comprador = :comprador, precio_venta = :precio WHERE id = :id");
        $stmt->execute([
            'comprador' => $nuevo_comprador,
            'precio' => $nuevo_precio,
            'id' => $id_venta
        ]);
        header("Location: ventas.php");
        exit();
    }
}

// Obtener ventas registradas
$ventas = $pdo->query("
    SELECT v.id, m.marca, m.modelo, v.comprador, v.fecha_venta, v.precio_venta 
    FROM ventas v
    JOIN motos m ON v.id_moto = m.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas de Motos</title>
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
            background-color: #28a745;
            color: white;
        }
        h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h2>Registrar Venta</h2>
    <form method="POST">
        <select name="id_moto" required>
            <option value="">Seleccione una moto</option>
            <?php foreach ($motosDisponibles as $moto): ?>
                <option value="<?php echo $moto['id']; ?>">
                    <?php echo $moto['marca'] . " " . $moto['modelo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="comprador" placeholder="Nombre del comprador" required>
        <input type="number" step="0.01" name="precio_venta" placeholder="Precio de venta" required>
        <button type="submit" name="registrar_venta">Registrar Venta</button>
    </form>

    <h2>Ventas Registradas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Moto</th>
            <th>Comprador</th>
            <th>Fecha de Venta</th>
            <th>Precio de Venta</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?php echo $venta['id']; ?></td>
                <td><?php echo $venta['marca'] . " " . $venta['modelo']; ?></td>
                <td><?php echo $venta['comprador']; ?></td>
                <td><?php echo $venta['fecha_venta']; ?></td>
                <td><?php echo $venta['precio_venta']; ?></td>
                <td class="action-buttons">
                    <!-- Formulario para eliminar la venta -->
                    <form method="POST" action="ventas.php">
                        <input type="hidden" name="id_venta" value="<?php echo $venta['id']; ?>">
                        <button type="submit" name="eliminar_venta" onclick="return confirm('¿Estás seguro de que quieres eliminar esta venta?')">Eliminar</button>
                    </form>
                    <!-- Formulario para editar la venta -->
                    <form method="POST" action="ventas.php">
                        <input type="hidden" name="id_venta" value="<?php echo $venta['id']; ?>">
                        <input type="text" name="nuevo_comprador" placeholder="Nuevo comprador" required>
                        <input type="number" step="0.01" name="nuevo_precio" placeholder="Nuevo precio" required>
                        <button type="submit" name="editar_venta">Editar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>

