<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'conexion.php';

// CRUD para motos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar'])) {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $anio = $_POST['anio'];
        $propietario = $_POST['propietario'];
        $precio = $_POST['precio'];

        $stmt = $pdo->prepare("INSERT INTO motos (marca, modelo, anio, propietario, precio) VALUES (:marca, :modelo, :anio, :propietario, :precio)");
        $stmt->execute(['marca' => $marca, 'modelo' => $modelo, 'anio' => $anio, 'propietario' => $propietario, 'precio' => $precio]);
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM motos WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $anio = $_POST['anio'];
        $propietario = $_POST['propietario'];
        $precio = $_POST['precio'];

        $stmt = $pdo->prepare("UPDATE motos SET marca = :marca, modelo = :modelo, anio = :anio, propietario = :propietario, precio = :precio WHERE id = :id");
        $stmt->execute([
            'id' => $id,
            'marca' => $marca,
            'modelo' => $modelo,
            'anio' => $anio,
            'propietario' => $propietario,
            'precio' => $precio
        ]);
    }
}

// Consultar motos
$motos = $pdo->query("SELECT * FROM motos")->fetchAll(PDO::FETCH_ASSOC);

// Consultar una moto específica para edición
$motoEditar = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM motos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $motoEditar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Motos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }
        main {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            text-align: center;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        form input, form button {
            flex: 1 1 calc(50% - 20px);
            padding: 10px;
            font-size: 16px;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
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
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f8f9fa;
        }
        .actions {
            display: flex;
            gap: 5px;
        }
        .actions button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .actions button:hover {
            background-color: #c82333;
        }
        .actions a {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Gestión de Motos</h1>
    </header>
    <main>
        <h2>Formulario de Moto</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $motoEditar['id'] ?? ''; ?>">
            <input type="text" name="marca" placeholder="Marca" value="<?php echo $motoEditar['marca'] ?? ''; ?>" required>
            <input type="text" name="modelo" placeholder="Modelo" value="<?php echo $motoEditar['modelo'] ?? ''; ?>" required>
            <input type="number" name="anio" placeholder="Año" value="<?php echo $motoEditar['anio'] ?? ''; ?>" required>
            <input type="text" name="propietario" placeholder="Propietario" value="<?php echo $motoEditar['propietario'] ?? ''; ?>" required>
            <input type="number" step="0.01" name="precio" placeholder="Precio" value="<?php echo $motoEditar['precio'] ?? ''; ?>" required>
            <?php if ($motoEditar): ?>
                <button type="submit" name="editar">Editar</button>
            <?php else: ?>
                <button type="submit" name="guardar">Guardar</button>
            <?php endif; ?>
        </form>

        <h3>Lista de Motos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Propietario</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($motos as $moto): ?>
                <tr>
                    <td><?php echo $moto['id']; ?></td>
                    <td><?php echo $moto['marca']; ?></td>
                    <td><?php echo $moto['modelo']; ?></td>
                    <td><?php echo $moto['anio']; ?></td>
                    <td><?php echo $moto['propietario']; ?></td>
                    <td><?php echo $moto['precio']; ?></td>
                    <td class="actions">
                        <a href="?edit_id=<?php echo $moto['id']; ?>">Editar</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $moto['id']; ?>">
                            <button type="submit" name="eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="dashboard.php">Volver</a>
    </main>
</body>
</html>

