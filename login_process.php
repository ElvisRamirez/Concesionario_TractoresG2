<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Datos de conexión disponibles
$credentials = [
    'postgres' => ['host' => 'localhost', 'dbname' => 'Concesionario_Tractores', 'user' => 'postgres', 'password' => '593'],
    'usuario1'=> ['host' => 'localhost', 'dbname' => 'Concesionario_Tractores', 'user' => 'usuario1', 'password' => 'usuario'],
    'admin1' => ['host' =>'localhost', 'dbname' => 'Concesionario_Tractores', 'user' => 'admin1', 'password' => 'admin'],
    'empleado1'=> ['host' => 'localhost', 'dbname' => 'Concesionario_Tractores', 'user' => 'empleado1', 'password' => 'empleado'],
    
];


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
