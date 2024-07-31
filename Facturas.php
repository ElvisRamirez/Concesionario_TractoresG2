<?php
<<<<<<< HEAD
// Configuración de conexión a la base de datos
//$dbHost = '10.241.0.57';
$dbHost = '192.168.1.10';
//$dbHost = '192.168.10.10';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';
=======
// Incluir el archivo de conexión
include 'conexion.php';
include "../permisos.php"; 
>>>>>>> b964678eef722a98cc3f7c5f82fbdc9559e0064f

// Consulta utilizando la vista para obtener datos de facturas con detalles
$sql = "SELECT * FROM VistaFacturasDetalles";

// Preparar y ejecutar la consulta
$stmt = $db->query($sql);
$facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta utilizando la vista para obtener los detalles de los alquileres
$queryDetalles = $db->prepare("SELECT * FROM VistaDetallesAlquileres");

$queryDetalles->execute();
$alquileres = $queryDetalles->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas y Detalles</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo adicional opcional para la tabla */
        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px; /* Espacio superior */
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        
    /* Estilo personalizado */
    body {
      padding-left: 15%; /* Ajusta el contenido para evitar que se superponga al nav */
      overflow-x: hidden; /* Evita la barra de desplazamiento horizontal */
    }
    .sidenav {
      height: 100%;
      width: 200px;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0; /* Menú visible por defecto */
      background-color: #f8f9fa;
      padding-top: 20px;
    }
    .sidenav a {
      padding: 10px 15px;
      text-decoration: none;
      font-size: 18px;
      color: #343a40;
      display: block;
    }
    .sidenav a:hover {
      background-color: #dee2e6; /* Cambia el color de fondo cuando se pasa el mouse sobre los enlaces */
    }
    .content {
      margin-left: 250px; /* Ajusta el margen izquierdo para dejar espacio para el menú */
    }
     /* Estilo personalizado */
  .row-with-transition {
    overflow-x: hidden;
  }
  .row-with-transition:hover .row {
    transform: translateX(-235px); /* Ajusta el desplazamiento según tus necesidades */
  }
  .row {
    transition: transform 0.4s ease; /* Agrega una transición suave al desplazamiento */
  }
  </style>
</head>
<body>

<div class="sidenav" id="mySidenav">
        <a href="../index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
        <a href="../Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
        <a href="../Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="../Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
        <a href="tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="../Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
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
    <div class="container mt-5">
    <h2 class="mb-4">Detalles de Alquileres</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Alquiler ID</th>
                <th>Cliente</th>
                <th>Empleado</th>
                <th>Tractor</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Total Alquiler</th>
            </tr>
        </thead>
        <tbody>
          
            <?php foreach ($alquileres as $alquiler): ?>
                <tr>
                    <td><?php echo htmlspecialchars($alquiler['alquilerid']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['cliente']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['empleado']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['marca'] . ' ' . $alquiler['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['preciounitario']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['cantidad']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['fechainicio']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['fechafin']); ?></td>
                    <td><?php echo htmlspecialchars($alquiler['totalalquiler']); ?></td>
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
