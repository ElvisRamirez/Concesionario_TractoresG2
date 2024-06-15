<?php
// Conexión a la base de datos
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Función para obtener todos los clientes
function obtenerClientes($db) {
    $query = $db->query("SELECT * FROM Clientes");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Función para generar enlace de edición
function generarEnlaceEditar($clienteID) {
    return "<a href='editar_cliente.php?id=$clienteID'><i class='fas fa-edit'></i> Editar</a>";
    
}

// Función para generar enlace de eliminación
function generarEnlaceEliminar($clienteID) {
    return "<a href='eliminar_cliente.php?id=$clienteID' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")'><i class='fas fa-trash'></i> Eliminar</a>";
}

// Mostrar tabla de clientes
function mostrarClientes($clientes) {
    echo "<table class='table'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                      <th>Cédula</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";
    
    foreach ($clientes as $cliente) {
        echo "<tr>
                <td>{$cliente['clienteid']}</td>
                <td>{$cliente['nombre']}</td>
                <td>{$cliente['apellido']}</td>
                 <td>{$cliente['cedula']}</td>
                <td>{$cliente['dirección']}</td>
                <td>{$cliente['teléfono']}</td>
                <td>{$cliente['email']}</td>
                <td>" . generarEnlaceEditar($cliente['clienteid']) . " | " . generarEnlaceEliminar($cliente['clienteid']) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
}
// Agregar nuevo cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $cedula = $_POST["cedula"]; // Recoge el valor del campo cedula correctamente
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    // Preparar la consulta SQL con los campos correctos
    $query = $db->prepare("INSERT INTO Clientes (nombre, apellido, cedula, dirección, teléfono, email) VALUES (?, ?, ?, ?, ?, ?)");
    $query->execute([$nombre, $apellido, $cedula, $direccion, $telefono, $email]);

    // Redirigir para evitar el reenvío del formulario
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
    /* Estilo personalizado */
    body {
        padding-left: 20%;
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
<body>

<div class="sidenav" id="mySidenav">
        <a href="../index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
        <a href="../Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
        <a href="../Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="../Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
        <a href="tractores.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="alquileres.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="mantenimientos.php"><i class="fas fa-tools mr-2"></i> Mantenimiento</a>
        <a href="facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
    <div class="container mt-5">
    <h2>Agregar Nuevo Cliente</h2>
    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required>
        </div>
        <div class="form-group">
            <label for="cedula">Cédula:</label>
            <input type="text" class="form-control" id="cedula" name="cedula" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Cliente</button>
    </form>
</div>


<div class="container mt-4">
    <h2>Lista de Clientes</h2>
    <?php
    $clientes = obtenerClientes($db);
    mostrarClientes($clientes);
    ?>
</div>

<!-- Scripts de Bootstrap y Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
