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
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());

    
}


   // Mostrar el mensaje de error si ocurre una excepción
  
// No se necesita redeclarar la función conectarBD() aquí si ya está en otro lugar
