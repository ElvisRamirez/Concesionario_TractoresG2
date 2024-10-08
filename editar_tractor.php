<?php
include "conexion.php";


// Verificar si se ha enviado un ID válido
if (!isset($_GET['tractorID']) || empty($_GET['tractorID'])) {
  die("ID de tractor no proporcionado.");
}

$tractorID = $_GET['tractorID'];

/// Obtener los detalles del tractor
$query = $db->prepare("SELECT T.*, M.Marca, M.Modelo FROM Tractores T INNER JOIN ModelosTractores M ON T.ModeloID = M.ModeloID WHERE TractorID = ?");
$query->execute([$tractorID]);
$tractor = $query->fetch(PDO::FETCH_ASSOC);

// Verificar si el tractor existe
if (!$tractor) {
  die("Tractor no encontrado.");
}

// Obtener el año y el estado del tractor si están definidos
$año = isset($tractor['Año']) ? $tractor['Año'] : "";
$estado = isset($tractor['Estado']) ? $tractor['Estado'] : "";


// Manejar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $año = intval($_POST["año"]);
  $estado = $_POST["estado"];

  // Actualizar el tractor en la base de datos
  $query = $db->prepare("UPDATE Tractores SET Año = ?, Estado = ? WHERE TractorID = ?");
  $query->execute([$año, $estado, $tractorID]);

  // Redirigir a la página principal después de la actualización
  header("Location: tractor.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Tractor</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* Estilo personalizado */
    body {
      padding-left: 20%;
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

  <body>

    <div class="sidenav" id="mySidenav">
      <a href="#"><i class="fas fa-user mr-2"> </i><?php echo htmlspecialchars($_SESSION['username']); ?></a>
      <a href="index.php"><i class="fas fa-home mr-2"></i> Inicio</a>

      <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
        <a href="../Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'Administrador') : ?>
        <a href="../Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="../Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
        <a href="../tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'Administrador' || $_SESSION['role'] === 'empleados') : ?>
        <a href="../Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="../alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="../Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'Administrador') : ?>
        <a href="../pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="../inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
      <?php endif; ?>

      <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
    </div>


    <div class="container mt-5">
      <h1>Editar Tractor</h1>
      <form method="post">
        <div class="form-group">
          <label for="año">Año:</label>
          <input type="number" class="form-control" id="año" name="año" value="<?php echo htmlspecialchars($año); ?>" min="1900" max="<?php echo date('Y'); ?>" required>
        </div>
        <div class="form-group">
          <label for="estado">Estado:</label>
          <select class="form-control" id="estado" name="estado" required>
            <option value="disponible" <?php if ($estado == 'disponible') echo 'selected'; ?>>Disponible</option>
            <option value="vendido" <?php if ($estado == 'vendido') echo 'selected'; ?>>Vendido</option>
            <option value="alquilado" <?php if ($estado == 'alquilado') echo 'selected'; ?>>Alquilado</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </form>

    </div>
  </body>

</html>