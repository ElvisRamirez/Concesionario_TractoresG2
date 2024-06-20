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
    die("ID de cliente no proporcionado.");
}

$clienteID = $_GET['id'];

// Verificar si el cliente existe
$query = $db->prepare("SELECT * FROM Clientes WHERE clienteid = ?");
$query->execute([$clienteID]);
$cliente = $query->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente no encontrado.");
}

// Eliminar el cliente
$query = $db->prepare("DELETE FROM Clientes WHERE clienteid = ?");
$query->execute([$clienteID]);

// Redirigir a la página principal después de eliminar
header("Location: /clientes.php");
exit;
?>
