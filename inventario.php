<?php
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    // Establecer conexión PDO
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL corregida para obtener datos del inventario
    $sql = "SELECT mt.Modelo, p.Nombre AS Proveedor, i.FechaIngreso, i.Cantidad, i.PrecioUnitario, i.PrecioCompra
            FROM Inventario i
            JOIN Tractores t ON i.TractorID = t.TractorID
            JOIN ModelosTractores mt ON t.ModeloID = mt.ModeloID
            JOIN Proveedores p ON i.ProveedorID = p.ProveedorID";

    // Preparar y ejecutar la consulta
    $stmt = $db->query($sql);
    $inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Tractores</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Inventario de Tractores</h2>
    <table>
        <thead>
            <tr>
                <th>Modelo</th>
                <th>Proveedor</th>
                <th>Fecha de Ingreso</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Precio de Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventario as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($item['proveedor']); ?></td>
                    <td><?php echo htmlspecialchars($item['fechaingreso']); ?></td>
                    <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($item['preciounitario']); ?></td>
                    <td><?php echo htmlspecialchars($item['preciocompra']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
