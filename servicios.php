<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'conexion.php';

// CRUD para servicios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar'])) {
        $id_moto = $_POST['id_moto'];
        $descripcion = $_POST['descripcion'];
        $fecha_servicio = $_POST['fecha_servicio'];
        $fecha_proximo_servicio = $_POST['fecha_proximo_servicio'];

        $stmt = $pdo->prepare("INSERT INTO servicios (id_moto, descripcion, fecha_servicio, fecha_proximo_servicio) 
                               VALUES (:id_moto, :descripcion, :fecha_servicio, :fecha_proximo_servicio)");
        $stmt->execute([
            'id_moto' => $id_moto,
            'descripcion' => $descripcion,
            'fecha_servicio' => $fecha_servicio,
            'fecha_proximo_servicio' => $fecha_proximo_servicio
        ]);
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

// Obtener servicios
$servicios = $pdo->query("
    SELECT s.*, m.marca, m.modelo 
    FROM servicios s
    JOIN motos m ON s.id_moto = m.id")->fetchAll(PDO::FETCH_ASSOC);

// Obtener motos
$motos = $pdo->query("SELECT * FROM motos")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Servicios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2 class="text-center mb-4">Historial de Servicios</h2>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Registrar un Nuevo Servicio</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="id_moto" class="form-label">Moto</label>
                        <select name="id_moto" id="id_moto" class="form-select" required>
                            <option value="">Seleccionar moto</option>
                            <?php foreach ($motos as $moto): ?>
                                <option value="<?php echo $moto['id']; ?>"><?php echo $moto['marca'] . " " . $moto['modelo']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción del Servicio</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese una descripción" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_servicio" class="form-label">Fecha del Servicio</label>
                        <input type="date" name="fecha_servicio" id="fecha_servicio" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_proximo_servicio" class="form-label">Fecha del Próximo Servicio</label>
                        <input type="date" name="fecha_proximo_servicio" id="fecha_proximo_servicio" class="form-control">
                    </div>
                    <button type="submit" name="guardar" class="btn btn-primary">Guardar Servicio</button>
                </form>
            </div>
        </div>

        <h3 class="text-center mb-3">Servicios Registrados</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Moto</th>
                        <th>Descripción</th>
                        <th>Fecha Servicio</th>
                        <th>Próximo Servicio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servicios as $servicio): ?>
                        <tr>
                            <td><?php echo $servicio['id']; ?></td>
                            <td><?php echo $servicio['marca'] . " " . $servicio['modelo']; ?></td>
                            <td><?php echo $servicio['descripcion']; ?></td>
                            <td><?php echo $servicio['fecha_servicio']; ?></td>
                            <td><?php echo $servicio['fecha_proximo_servicio'] ?: 'N/A'; ?></td>
                            <td>
                                <a href="editar_servicio.php?id=<?php echo $servicio['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $servicio['id']; ?>">
                                    <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

