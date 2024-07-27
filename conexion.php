<?php
session_start();

// Verificar que el usuario está autenticado
if (!isset($_SESSION['username'])) {
    die("No estás autenticado.");
}

// Recoger datos de conexión de la sesión
$dbHost = $_SESSION['dbHost'];
$dbName = $_SESSION['dbName'];
$dbUser = $_SESSION['dbUser'];
$dbPass = $_SESSION['dbPass'];

try {
    // Establecer conexión PDO
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Aquí puedes realizar otras configuraciones o consultas iniciales si es necesario
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
