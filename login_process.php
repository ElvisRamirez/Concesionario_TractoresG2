<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Datos de conexión disponibles

// Recoger datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

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
    echo "Nombre de usuario o contraseña incorrectos.";
}
?>
