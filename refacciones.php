<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'conexion.php';

// CRUD para refacciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar'])) {
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];

        $stmt = $pdo->prepare("INSERT INTO refacciones (nombre, tipo, cantidad, precio) VALUES (:nombre, :tipo, :cantidad, :precio)");
        $stmt->execute([
            'nombre' => $nombre,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'precio' => $precio
        ]);
    }

    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM refacciones WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    if (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];

        $stmt = $pdo->prepare("UPDATE refacciones SET nombre = :nombre, tipo = :tipo, cantidad = :cantidad, precio = :precio WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'precio' => $precio
        ]);
    }
}

// Obtener refacciones
$refacciones = $pdo->query("SELECT * FROM refacciones")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Refacciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        form input, form button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        form button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f1f1f1;
        }
        .acciones form {
            display: inline;
        }
        .acciones button {
            margin: 5px;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .acciones .eliminar {
            background-color: #dc3545;
            color: white;
        }
        .acciones .eliminar:hover {
            background-color: #c82333;
        }
        .acciones .editar {
            background-color: #007bff;
            color: white;
        }
        .acciones .editar:hover {
            background-color: #0069d9;
        }
    </style>
</head>
<body>

<header>
    <h2>Gestión de Refacciones</h2>
</header>

<div class="container">
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="tipo" placeholder="Tipo" required><br>
        <input type="number" name="cantidad" placeholder="Cantidad" required><br>
        <input type="number" step="0.01" name="precio" placeholder="Precio" required><br>
        <button type="submit" name="guardar">Guardar Refacción</button>
    </form>

    <h3>Refacciones Registradas</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($refacciones as $refaccion): ?>
            <tr>
                <td><?php echo $refaccion['id']; ?></td>
                <td><?php echo $refaccion['nombre']; ?></td>
                <td><?php echo $refaccion['tipo']; ?></td>
                <td><?php echo $refaccion['cantidad']; ?></td>
                <td><?php echo $refaccion['precio']; ?></td>
                <td class="acciones">
                    <!-- Botón de eliminar -->
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $refaccion['id']; ?>">
                        <button type="submit" name="eliminar" class="eliminar">Eliminar</button>
                    </form>
                    <!-- Botón de editar -->
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo $refaccion['id']; ?>">
                        <input type="text" name="nombre" value="<?php echo $refaccion['nombre']; ?>" required>
                        <input type="text" name="tipo" value="<?php echo $refaccion['tipo']; ?>" required>
                        <input type="number" name="cantidad" value="<?php echo $refaccion['cantidad']; ?>" required>
                        <input type="number" step="0.01" name="precio" value="<?php echo $refaccion['precio']; ?>" required>
                        <button type="submit" name="editar" class="editar">Editar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="dashboard.php">Volver</a>
</div>

</body>
</html>

