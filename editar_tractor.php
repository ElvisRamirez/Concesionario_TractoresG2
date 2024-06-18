<?php
// Conexión a la base de datos (misma configuración que tractor.php)
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

// Verificar si se ha enviado un ID válido
if (!isset($_GET['tractorID']) || empty($_GET['tractorID'])) {
    die("ID de tractor no proporcionado.");
}
$modeloID = isset($tractor['ModeloID']) ? $tractor['ModeloID'] : "";
$año = isset($tractor['Año']) ? $tractor['Año'] : "";
$estado = isset($tractor['Estado']) ? $tractor['Estado'] : "";

$tractorID = $_GET['tractorID'];

// Obtener los detalles del tractor
$query = $db->prepare("SELECT T.*, M.Marca, M.Modelo FROM Tractores T INNER JOIN ModelosTractores M ON T.ModeloID = M.ModeloID WHERE TractorID = ?");
$query->execute([$tractorID]);
$tractor = $query->fetch(PDO::FETCH_ASSOC);

// Verificar si el tractor existe
if (!$tractor) {
    die("Tractor no encontrado.");
}

// Obtener todos los modelos de tractores
$queryModelos = $db->query("SELECT * FROM ModelosTractores");
$modelos = $queryModelos->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variables con los valores actuales del tractor
$modeloID = isset($tractor['ModeloID']) ? $tractor['ModeloID'] : "";
$año = isset($tractor['Año']) ? $tractor['Año'] : "";
$estado = isset($tractor['Estado']) ? $tractor['Estado'] : "";

// Manejar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modeloID = $_POST["modeloID"];
    $año = $_POST["año"];
    $estado = $_POST["estado"];

    // Actualizar el tractor en la base de datos
    $query = $db->prepare("UPDATE Tractores SET ModeloID = ?, Año = ?, Estado = ? WHERE TractorID = ?");
    $query->execute([$modeloID, $año, $estado, $tractorID]);

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
    <style>
    /* Estilo personalizado */
    body {
      padding-top: 56px; /* Ajusta el contenido para evitar que se superponga al nav */
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
        <a href="../tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="../Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="../alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="../Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="../pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="../inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
    <div class="container mt-5">
        <h1>Editar Tractor</h1>
        <form method="post">
            <div class="form-group">
                <label for="modeloID">Modelo:</label>
                <select class="form-control" id="modeloID" name="modeloID" required>
    <?php foreach ($modelos as $modelo): ?>
        <option value="<?php echo $modelo['ModeloID']; ?>" <?php if ($modelo['ModeloID'] == $modeloID) echo 'selected'; ?>>
            <?php echo $modelo['Marca'] . ' - ' . $modelo['Modelo']; ?>
        </option>
    <?php endforeach; ?>
</select>

            </div>
            <div class="form-group">
                <label for="año">Año:</label>
                <input type="number" class="form-control" id="año" name="año" value="<?php echo $año; ?>" required>
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
