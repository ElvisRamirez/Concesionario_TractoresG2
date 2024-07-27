<?php
include "../conexion.php";
// Verificar si se ha enviado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de proveedor no proporcionado.");
}

$proveedorID = $_GET['id'];

// Eliminar el proveedor
$query = $db->prepare("DELETE FROM Proveedores WHERE ProveedorID = ?");
$query->execute([$proveedorID]);

// Redirigir a la página principal después de la eliminación
header("Location:proveedores.php");
exit;
?>
