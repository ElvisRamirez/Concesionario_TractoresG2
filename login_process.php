<?php
// Habilitar reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Datos de conexión disponibles
$credentials = [
    'postgres' => ['host' => '192.168.10.208', 'dbname' => 'postgres', 'user' => 'postgres', 'password' => '593'],
    'usuario1' => ['host' => '192.168.10.208', 'dbname' => 'postgres', 'user' => 'usuario1', 'password' => 'usuario'],
    'admin1' => ['host' => '192.168.10.208', 'dbname' => 'postgres', 'user' => 'admin1', 'password' => 'admin'],
    'empleado1' => ['host' => '192.168.10.208', 'dbname' => 'postgres', 'user' => 'empleado1', 'password' => 'empleado'],
];

// Recoger datos del formulario
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Verificar credenciales
if (isset($credentials[$username]) && $credentials[$username]['password'] === $password) {
    $_SESSION['dbHost'] = $credentials[$username]['host'];
    $_SESSION['dbName'] = $credentials[$username]['dbname'];
    $_SESSION['dbUser'] = $credentials[$username]['user'];
    $_SESSION['dbPass'] = $credentials[$username]['password'];
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $credentials[$username]['role']; // Asignación del rol
    // Redirigir a la página principal
    header("Location: index.php");
    exit;
} else {
    // Credenciales incorrectas
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    </head>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Nombre de usuario o contraseña incorrectos.",
                footer: "<a href=\"#\">¿Por qué tengo este problema?</a>"
            }).then(function() {
                window.location.href = "login.php"; // Redirige al formulario de login
            });
        </script>
    </body>
    </html>';
}
