<?php
// Incluir el archivo de conexión
include 'conexion.php';

 // Consulta SQL para obtener datos de pagos desde la vista
 $sql = "SELECT * FROM vista_pagos_con_detalles";

 // Preparar y ejecutar la consulta
 $stmt = $db->query($sql);
 $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Aquí puedes procesar o mostrar los datos obtenidos
// Por ejemplo, mostrar en una tabla HTML o procesar los datos para alguna otra lógica
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pagos</title>
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
      
      padding-left:15%; /* Ajusta el contenido para evitar que se superponga al nav */
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
<a  href="#"><i class="fas fa-user mr-2" >  </i><?php echo htmlspecialchars($username); ?></a>
        <a href="../index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
        <a href="Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
        <a href="../Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="../Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
        <a href="tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="../alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
    <div class="container">
        <h2 class="mt-4 mb-4">Lista de Pagos</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Pago ID</th>
                    <th>Factura ID</th>
                    <th>Forma de Pago</th>
                    <th>Fecha de Pago</th>
                    <th>Monto de Pago</th>
                    <th>Fecha de Factura</th>
                    <th>Total Factura</th>
                    <th>Descripción Detalle</th>
                    <th>Precio Unitario Detalle</th>
                    <th>Cantidad Detalle</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $pago): ?>
                    <tr>
                        <td><?php echo isset($pago['pagoid']) ? htmlspecialchars($pago['pagoid']) : ''; ?></td>
                        <td><?php echo isset($pago['facturaid']) ? htmlspecialchars($pago['facturaid']) : ''; ?></td>
                        <td><?php echo isset($pago['formapago']) ? htmlspecialchars($pago['formapago']) : ''; ?></td>
                        <td><?php echo isset($pago['fechapago']) ? htmlspecialchars($pago['fechapago']) : ''; ?></td>
                        <td><?php echo isset($pago['montopago']) ? htmlspecialchars($pago['montopago']) : ''; ?></td>
                        <td><?php echo isset($pago['fechafactura']) ? htmlspecialchars($pago['fechafactura']) : ''; ?></td>
                        <td><?php echo isset($pago['totalfactura']) ? htmlspecialchars($pago['totalfactura']) : ''; ?></td>
                        <td><?php echo isset($pago['descripciondetalle']) ? htmlspecialchars($pago['descripciondetalle']) : ''; ?></td>
                        <td><?php echo isset($pago['preciounitariodetalle']) ? htmlspecialchars($pago['preciounitariodetalle']) : ''; ?></td>
                        <td><?php echo isset($pago['cantidaddetalle']) ? htmlspecialchars($pago['cantidaddetalle']) : ''; ?></td>
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
