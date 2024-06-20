<?php
// Conexión a la base de datos
//$dbHost = '10.241.0.48';
$dbHost = '192.168.10.10';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Función para obtener todos los empleados
function obtenerEmpleados($db) {
    $query = $db->query("SELECT * FROM Empleados ORDER BY empleadoid ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Función para generar enlace de edición
function generarEnlaceEditar($empleados) {
    return "<a href='/Form_Empleado/editar.php?id=$empleados'><i class='fas fa-edit'></i> Editar</a>";
}

// Función para generar enlace de eliminación
function generarEnlaceEliminar($empleados) {
    return "<a href='/Form_Empleado/eliminar.php?id=$empleados' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")'><i class='fas fa-trash'></i> Eliminar</a>";
}

// Mostrar tabla de empleados
function mostrarEmpleados($empleados) {
    echo "<div class='container mt-4'>
            <table class='table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Puesto</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
    
    foreach ($empleados as $empleado) {
        echo "<tr>
                <td>{$empleado['empleadoid']}</td>
                <td>{$empleado['nombre']}</td>
                <td>{$empleado['apellido']}</td>
                <td>{$empleado['puesto']}</td>
                <td>{$empleado['cedula']}</td>
                <td>{$empleado['teléfono']}</td>
                <td>{$empleado['email']}</td>
                <td>" . generarEnlaceEditar($empleado['empleadoid']) . " | " . generarEnlaceEliminar($empleado['empleadoid']) . "</td>
              </tr>";
    }

    echo "</tbody></table></div>";
}

// Agregar nuevo empleado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $puesto = $_POST["puesto"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $cedula = $_POST["cedula"];

    // Verificar si la cédula ya existe
    $query = $db->prepare("SELECT COUNT(*) FROM Empleados WHERE cedula = ?");
    $query->execute([$cedula]);
    $count = $query->fetchColumn();

    if ($count > 0) {
        echo "<div class='alert alert-danger'>Error: La cédula ya existe en la base de datos.</div>";
    } else {
        $query = $db->prepare("INSERT INTO Empleados (nombre, apellido, puesto, cedula, teléfono, email) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([$nombre, $apellido, $puesto, $cedula, $telefono, $email]);
        // Recargar la página para actualizar la tabla
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores - Empleados</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 
    <style>
    /* Estilo personalizado */
    body {
        padding-left: 12%;
        overflow-x: hidden;
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
    <div class="card">
    <div class="card-body">
        <div class="container">
            <h3 class="card-title text-center">Agregar Nuevo Empleado</h3>
            <form method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="puesto" name="puesto" placeholder="Puesto" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Empleado</button>
            </form>
        </div>
    </div>
</div>


    <div class="container mt-4">
        <h2>Lista de Empleados</h2>
        <?php
        $empleados = obtenerEmpleados($db);
        mostrarEmpleados($empleados);
        ?>
    </div>
</div>

<!-- Scripts de Bootstrap y Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src
