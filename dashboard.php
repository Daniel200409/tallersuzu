<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Taller de Motos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h2 {
            margin-top: 20px;
            color: #333;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 30px;
        }

        .button {
            width: 200px;
            height: 60px;
            margin: 10px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            color: #fff;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .button:hover {
            transform: translateY(-3px);
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.2);
        }

        .motos {
            background-color: #007bff; /* Azul */
        }

        .servicios {
            background-color: #28a745; /* Verde */
        }

        .refacciones {
            background-color: #ffc107; /* Amarillo */
        }

        .inventario {
            background-color: #17a2b8; /* Cian */
        }

        .ventas {
            background-color: #dc3545; /* Rojo */
        }

        .logout {
            background-color: #6c757d; /* Gris */
        }
    </style>
</head>
<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h2>
    <div class="container">
        <a href="motos.php"><button class="button motos">Gestión de Motos</button></a>
        <a href="servicios.php"><button class="button servicios">Historial de Servicios</button></a>
        <a href="refacciones.php"><button class="button refacciones">Gestión de Refacciones</button></a>
        <a href="inventario.php"><button class="button inventario">Inventario General</button></a>
        <a href="ventas.php"><button class="button ventas">Ventas de Motos</button></a>
        <a href="logout.php"><button class="button logout">Cerrar Sesión</button></a>
    </div>
</body>
</html>

