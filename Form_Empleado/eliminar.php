<?php
// Conexión a la base de datos
$dbHost = '10.241.0.57';
//$dbHost = '10.241.0.48';
//$dbHost = '192.168.10.10';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Verificar si se ha pasado el ID del empleado a eliminar
if (isset($_GET['id'])) {
    $empleadoID = $_GET['id'];

    // Preparar la consulta de eliminación
    $query = $db->prepare("DELETE FROM Empleados WHERE empleadoid = ?");
    $query->execute([$empleadoID]);

    // Redirigir de nuevo a la página de empleados
    header("Location: empleados.php");
    exit;
} else {
    echo "ID de empleado no especificado.";
}
?>