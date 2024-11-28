<?php
$host = 'localhost';
$dbname = 'taller_motos';
$user = 'root'; // Cambiar según configuración
$password = ''; // Cambiar según configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>

