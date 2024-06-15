<?php
// Conexión a la base de datos
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para mostrar errores de PDO
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Función para obtener nombre y apellido por cédula de cliente
function obtenerNombreApellidoCliente($db, $cedula) {
    $query = $db->prepare("SELECT nombre, apellido FROM Clientes WHERE cedula = ?");
    $query->execute([$cedula]);
    $resultado = $query->fetch(PDO::FETCH_ASSOC);
    return $resultado;
}

// Función para obtener nombre y apellido por cédula de empleado
function obtenerNombreApellidoEmpleado($db, $cedula) {
    $query = $db->prepare("SELECT nombre, apellido FROM Empleados WHERE cedula = ?");
    $query->execute([$cedula]);
    $resultado = $query->fetch(PDO::FETCH_ASSOC);
    return $resultado;
}

// Procesar el formulario cuando se envía
$nombreCliente = "";
$apellidoCliente = "";
$nombreEmpleado = "";
$apellidoEmpleado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["buscarCedulaCliente"])) {
        $buscarCedulaCliente = $_POST["buscarCedulaCliente"];
        
        // Buscar el nombre y apellido del cliente por cédula
        $cliente = obtenerNombreApellidoCliente($db, $buscarCedulaCliente);
        if ($cliente) {
            $nombreCliente = $cliente['nombre'];
            $apellidoCliente = $cliente['apellido'];
        } else {
            $nombreCliente = "";
            $apellidoCliente = "";
        }
    }

    if (isset($_POST["buscarCedulaEmpleado"])) {
        $buscarCedulaEmpleado = $_POST["buscarCedulaEmpleado"];
        
        // Buscar el nombre y apellido del empleado por cédula
        $empleado = obtenerNombreApellidoEmpleado($db, $buscarCedulaEmpleado);
        if ($empleado) {
            $nombreEmpleado = $empleado['nombre'];
            $apellidoEmpleado = $empleado['apellido'];
        } else {
            $nombreEmpleado = "";
            $apellidoEmpleado = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores - Realizar Venta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="sidenav" id="mySidenav">
    <!-- Links a otras secciones (no relevantes para este formulario) -->
</div>

<div class="container mt-5">
    <h2>Realizar Nueva Venta</h2>
    <form id="formVenta" method="post" action="">
        <div class="form-group">
            <label for="buscarCedulaCliente">Buscar por Cédula del Cliente:</label>
            <input type="text" class="form-control" id="buscarCedulaCliente" name="buscarCedulaCliente" placeholder="Ingrese la cédula del cliente..." value="<?php echo isset($buscarCedulaCliente) ? htmlspecialchars($buscarCedulaCliente) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="nombreCliente">Nombre Cliente:</label>
            <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="<?php echo htmlspecialchars($nombreCliente); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="apellidoCliente">Apellido Cliente:</label>
            <input type="text" class="form-control" id="apellidoCliente" name="apellidoCliente" value="<?php echo htmlspecialchars($apellidoCliente); ?>" readonly>
        </div>
        <hr> <!-- Separador para claridad -->

        <div class="form-group">
            <label for="buscarCedulaEmpleado">Buscar por Cédula del Empleado:</label>
            <input type="text" class="form-control" id="buscarCedulaEmpleado" name="buscarCedulaEmpleado" placeholder="Ingrese la cédula del empleado..." value="<?php echo isset($buscarCedulaEmpleado) ? htmlspecialchars($buscarCedulaEmpleado) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="nombreEmpleado">Nombre Empleado:</label>
            <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="apellidoEmpleado">Apellido Empleado:</label>
            <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado" value="<?php echo htmlspecialchars($apellidoEmpleado); ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Realizar Venta</button>
    </form>
</div>

<!-- Scripts de Bootstrap y Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
