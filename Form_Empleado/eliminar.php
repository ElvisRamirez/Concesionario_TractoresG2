<?php
// Conexión a la base de datos
//$dbHost = '10.241.0.48';
$dbHost = '192.168.10.10';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Verificar si se ha enviado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de empleado no proporcionado.");
}

$empleadoID = $_GET['id'];

// Verificar si el empleado existe
$query = $db->prepare("SELECT * FROM Empleados WHERE empleadoid = ?");
$query->execute([$empleadoID]);
$empleado = $query->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    die("Empleado no encontrado.");
}

// Eliminar el empleado
$query = $db->prepare("DELETE FROM Empleados WHERE empleadoid = ?");
$query->execute([$empleadoID]);

// Redirigir a la página principal después de eliminar
header("Location: empleados.php");
exit;
?>
