<?php
include "../conexion.php";

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