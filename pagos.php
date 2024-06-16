<?php
// Conexión a la base de datos
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Incluir el archivo de conexión a la base de datos

// Consulta SQL para obtener los pagos con información del cliente
$query = "SELECT
            p.PagoID,
            p.FacturaID,
            p.FormaPago,
            p.FechaPago,
            p.MontoPago,
            c.Nombre AS NombreCliente,
            c.Apellido AS ApellidoCliente
          FROM Pagos p
          INNER JOIN Facturas f ON p.FacturaID = f.FacturaID
          INNER JOIN Clientes c ON f.ClienteID = c.ClienteID";

try {
    $statement = $db->query($query);
    $pagos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al ejecutar la consulta: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pagos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .img-thumbnail {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Lista de Pagos</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Pago ID</th>
                    <th>Factura ID</th>
                    <th>Forma de Pago</th>
                    <th>Fecha de Pago</th>
                    <th>Monto de Pago</th>
                    <th>Nombre Cliente</th>
                    <th>Apellido Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $pago): ?>
                    <tr>
                        <td><?php echo isset($pago['PagoID']) ? htmlspecialchars($pago['PagoID']) : ''; ?></td>
                        <td><?php echo isset($pago['FacturaID']) ? htmlspecialchars($pago['FacturaID']) : ''; ?></td>
                        <td><?php echo isset($pago['FormaPago']) ? htmlspecialchars($pago['FormaPago']) : ''; ?></td>
                        <td><?php echo isset($pago['FechaPago']) ? htmlspecialchars($pago['FechaPago']) : ''; ?></td>
                        <td><?php echo isset($pago['MontoPago']) ? htmlspecialchars($pago['MontoPago']) : ''; ?></td>
                        <td><?php echo isset($pago['NombreCliente']) ? htmlspecialchars($pago['NombreCliente']) : ''; ?></td>
                        <td><?php echo isset($pago['ApellidoCliente']) ? htmlspecialchars($pago['ApellidoCliente']) : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
