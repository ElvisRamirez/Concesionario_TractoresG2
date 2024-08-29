<?php
session_start();
$username = $_SESSION['username'] ?? '';
$userRole = $_SESSION['userRole'] ?? '';

// Verificar que el usuario está autenticado
if (empty($username)) {
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

    // Aquí puedes realizar otras operaciones si es necesario
} catch (PDOException $e) {
    // Detectar el error específico de permisos
    if ($e->getCode() === '42501') {
        // Redirigir a error.php para errores de permisos
        header('Location: error.php?error=permissions');
        exit();
    } else {
        // Redirigir a error.php para otros errores
        header('Location: error.php?error=general');
        exit();
    }
}
