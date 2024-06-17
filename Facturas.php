<?php
// Configuración de conexión a la base de datos
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    // Establecer conexión PDO
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener datos de facturas con sus detalles
    $sql = "SELECT
                f.FacturaID,
                f.FechaFactura,
                c.Nombre AS NombreCliente,
                c.Apellido AS ApellidoCliente,
                e.Nombre AS NombreEmpleado,
                e.Apellido AS ApellidoEmpleado,
                df.Descripcion AS DescripcionDetalle,
                df.PrecioUnitario AS PrecioUnitarioDetalle,
                df.Cantidad AS CantidadDetalle
            FROM Facturas f
            INNER JOIN Clientes c ON f.ClienteID = c.ClienteID
            INNER JOIN Empleados e ON f.EmpleadoID = e.EmpleadoID
            INNER JOIN DetallesFactura df ON f.FacturaID = df.FacturaID
            ORDER BY f.FacturaID";

    // Preparar y ejecutar la consulta
    $stmt = $db->query($sql);
    $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas y Detalles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo adicional opcional para la tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Espacio superior */
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Facturas y Detalles</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Factura ID</th>
                    <th>Fecha de Factura</th>
                    <th>Cliente</th>
                    <th>Empleado</th>
                    <th>Descripción Detalle</th>
                    <th>Precio Unitario Detalle</th>
                    <th>Cantidad Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($factura['facturaid']); ?></td>
                        <td><?php echo htmlspecialchars($factura['fechafactura']); ?></td>
                        <td><?php echo htmlspecialchars($factura['nombrecliente'] . ' ' . $factura['apellidocliente']); ?></td>
                        <td><?php echo htmlspecialchars($factura['nombreempleado'] . ' ' . $factura['apellidoempleado']); ?></td>
                        <td><?php echo htmlspecialchars($factura['descripciondetalle']); ?></td>
                        <td><?php echo htmlspecialchars($factura['preciounitariodetalle']); ?></td>
                        <td><?php echo htmlspecialchars($factura['cantidaddetalle']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts de Bootstrap (jQuery y Popper.js necesarios para Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
