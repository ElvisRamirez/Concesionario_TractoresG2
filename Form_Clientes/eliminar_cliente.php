<?php
include "../conexion.php";
include "../permisos.php"; 
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
header("Location: ../Form_Clientes/clientes.php");
exit;
?>
