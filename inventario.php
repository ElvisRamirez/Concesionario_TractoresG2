<?php
// Incluir el archivo de conexión
include 'conexion.php';

try {
  // Consulta SQL para obtener datos del inventario desde la vista
  $sql = "SELECT * FROM vista_inventario";

  // Preparar y ejecutar la consulta
  $stmt = $db->query($sql);
  $inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Aquí puedes procesar o mostrar los datos obtenidos
  // Por ejemplo, mostrar en una tabla HTML o procesar los datos para alguna otra lógica

} catch (PDOException $e) {
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
  echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No tienes permisos para ver las Inventario!',
                    willClose: () => {
                        window.location.href = 'http://localhost:3000/index.php'; // Cambia '/' por la URL de tu página principal
                    }
                });
            });
          </script>";
  return [];
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Agregar la biblioteca de iconos de Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <title>Inventario de Tractores</title>
  <style>
    table {

      width: 90%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: right;
    }

    /* Estilo personalizado */
    body {
      padding-lefT: 20%;
      padding-top: 56px;
      /* Ajusta el contenido para evitar que se superponga al nav */
      overflow-x: hidden;
      /* Evita la barra de desplazamiento horizontal */
    }

    .sidenav {
      height: 100%;
      width: 200px;
      position: fixed;
      z-index: 1;
      top: 0;
      left: 0;
      /* Menú visible por defecto */
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
      background-color: transparent;
      /* No cambiar el color de fondo */
      border-bottom: 2px solid #367c2b;
      color: #367c2b;
      /* Cambiar el color del texto */
      /* Añadir una línea en la parte inferior */
    }

    .content {
      margin-left: 250px;
      /* Ajusta el margen izquierdo para dejar espacio para el menú */
    }

    /* Estilo personalizado */
    .row-with-transition {
      overflow-x: hidden;
    }

    .row-with-transition:hover .row {
      transform: translateX(-235px);
      /* Ajusta el desplazamiento según tus necesidades */
    }

    .row {
      transition: transform 0.4s ease;
      /* Agrega una transición suave al desplazamiento */
    }
  </style>
</head>

<body>

  <div class="sidenav" id="mySidenav">
    <a href="#"><i class="fas fa-user mr-2"> </i><?php echo htmlspecialchars($_SESSION['username']); ?></a>
    <a href="index.php"><i class="fas fa-home mr-2"></i> Inicio</a>

    <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
      <a href="Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'Administrador') : ?>
      <a href="Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
      <a href="Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
      <a href="tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
      <a href="Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
      <a href="alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
      <a href="Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'Administrador') : ?>
      <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
      <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    <?php endif; ?>

    <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
  </div>

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
      <?php foreach ($inventario as $item) : ?>
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
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>