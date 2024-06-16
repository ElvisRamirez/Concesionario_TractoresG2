<?php
// Conexión a la base de datos
$dbHost = 'localhost';
$dbName = 'Concesionario_Tractores';
$dbUser = 'postgres';
$dbPass = '593';
$alquilerID = null;

try {
    $db = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para mostrar errores de PDO
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Otras partes de tu código...

$mensajeError = ""; // Variable para almacenar mensajes de error
$mensajeAlquiler = ""; // Variable para almacenar mensajes de éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["buscarCedulaCliente"])) {
        $buscarCedulaCliente = $_POST["buscarCedulaCliente"];
        
        // Buscar el ID, nombre y apellido del cliente por cédula
        $cliente = obtenerClientePorCedula($db, $buscarCedulaCliente);
        if ($cliente) {
            $clienteID = $cliente['clienteid'];
            $nombreCliente = $cliente['nombre'];
            $apellidoCliente = $cliente['apellido'];
        } else {
            $mensajeError = "Cliente no encontrado.";
        }
    }

    if (isset($_POST["buscarCedulaEmpleado"])) {
        $buscarCedulaEmpleado = $_POST["buscarCedulaEmpleado"];
        
        // Buscar el ID, nombre y apellido del empleado por cédula
        $empleado = obtenerEmpleadoPorCedula($db, $buscarCedulaEmpleado);
        if ($empleado) {
            $empleadoID = $empleado['empleadoid'];
            $nombreEmpleado = $empleado['nombre'];
            $apellidoEmpleado = $empleado['apellido'];
        } else {
            $mensajeError = "Empleado no encontrado.";
        }
    }

    // Obtener la lista de tractores disponibles
    $tractoresDisponibles = obtenerTractoresDisponibles($db);

    if (isset($_POST["idTractorSeleccionado"])) {
        $idTractorSeleccionado = $_POST["idTractorSeleccionado"];
        
        // Validar y procesar fechas de inicio y fin del alquiler
        $fechaInicio = isset($_POST["fechaInicio"]) ? $_POST["fechaInicio"] : null;
        $fechaFin = isset($_POST["fechaFin"]) ? $_POST["fechaFin"] : null;

        if (!$fechaInicio || !$fechaFin) {
            $mensajeError = "Debe seleccionar ambas fechas de inicio y fin del alquiler.";
        } else {
            // Validar que la fecha de fin sea posterior a la fecha de inicio
            if (strtotime($fechaFin) <= strtotime($fechaInicio)) {
                $mensajeError = "La fecha de fin debe ser posterior a la fecha de inicio.";
            } else {
                // Calcular el total del alquiler basado en el precio por día ingresado
                $precioPorDia = isset($_POST["precioPorDia"]) ? floatval($_POST["precioPorDia"]) : 0.00;
                if ($precioPorDia <= 0) {
                    $mensajeError = "El precio por día debe ser mayor que cero.";
                } else {
                    // Obtener la cantidad de tractores seleccionada
                    $cantidad = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 1;

                    try {
                        // Realizar el alquiler con la cantidad de tractores
                        $alquilerID = realizarAlquiler($db, $clienteID, $empleadoID, $idTractorSeleccionado, $fechaInicio, $fechaFin, $precioPorDia, $cantidad);

                        $mensajeAlquiler = "Alquiler registrado con éxito. ID del alquiler: " . $alquilerID;
                    } catch (PDOException $e) {
                        $mensajeError = "Error al realizar el alquiler: " . $e->getMessage();
                    }
                }
            }
        }
    } else {
        // Obtener la lista de tractores disponibles al cargar la página
        $tractoresDisponibles = obtenerTractoresDisponibles($db);
    }
}

// Aquí se mostrarán los mensajes de error y éxito
if (!empty($mensajeError)) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($mensajeError) . '</div>';
}
if (!empty($mensajeAlquiler)) {
    echo '<div class="alert alert-success">' . htmlspecialchars($mensajeAlquiler) . '</div>';
}

// Resto de tu código HTML y formularios...

// Procesar el formulario cuando se envía
$nombreCliente = "";
$apellidoCliente = "";
$nombreEmpleado = "";
$apellidoEmpleado = "";
$tractoresDisponibles = [];
$totalAlquiler = 0.00;
$mensajeError = "";
$mensajeAlquiler = "";


// Función para realizar un alquiler y calcular el total del alquiler
function realizarAlquiler($db, $clienteID, $empleadoID, $tractorID, $fechaInicio, $fechaFin, $precioPorDia, $cantidad) {
    try {
        // Calcular días de alquiler
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);
        $diferencia = $inicio->diff($fin);
        $diasAlquiler = $diferencia->days + 1; // Sumar 1 para incluir el último día

        // Calcular total de alquiler
        $totalAlquiler = $diasAlquiler * $precioPorDia * $cantidad;

        // Iniciar transacción
        $db->beginTransaction();

        // Insertar en tabla DetallesAlquiler
        $queryAlquiler = $db->prepare("
            INSERT INTO DetallesAlquiler (AlquilerID, TractorID, PrecioUnitario, Cantidad)
            VALUES (?, ?, ?, ?)
            RETURNING DetalleAlquilerID
        ");
        $queryAlquiler->execute([$alquilerID, $tractorID, $precioPorDia, $cantidad]);

        $detalleAlquilerID = $queryAlquiler->fetchColumn();

        // Confirmar transacción
        $db->commit();
        
        return $detalleAlquilerID; // Devolver el ID del detalle de alquiler para su uso posterior
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $db->rollBack();
        throw $e;
    }
}

// Función para obtener el ID, nombre y apellido por cédula de cliente
function obtenerClientePorCedula($db, $cedula) {
    $query = $db->prepare("SELECT ClienteID, Nombre, Apellido FROM Clientes WHERE Cedula = ?");
    $query->execute([$cedula]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener el ID, nombre y apellido por cédula de empleado
function obtenerEmpleadoPorCedula($db, $cedula) {
    $query = $db->prepare("SELECT EmpleadoID, Nombre, Apellido FROM Empleados WHERE Cedula = ?");
    $query->execute([$cedula]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener tractores disponibles con modelo y marca
function obtenerTractoresDisponibles($db) {
    $query = $db->prepare("
        SELECT t.TractorID, m.Marca, m.Modelo
        FROM Tractores t
        INNER JOIN ModelosTractores m ON t.ModeloID = m.ModeloID
        WHERE t.Estado = 'disponible'
    ");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores - Realizar Alquiler</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Realizar Nuevo Alquiler</h2>
    <?php if ($mensajeError): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
    <?php endif; ?>
    <?php if ($mensajeAlquiler): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensajeAlquiler); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="buscarCedulaCliente">Cédula del Cliente:</label>
            <input type="text" class="form-control" id="buscarCedulaCliente" name="buscarCedulaCliente" placeholder="Ingrese la cédula del cliente" value="<?php echo isset($buscarCedulaCliente) ? htmlspecialchars($buscarCedulaCliente) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="nombreCliente">Nombre Cliente:</label>
            <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="<?php echo htmlspecialchars($nombreCliente); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="apellidoCliente">Apellido Cliente:</label>
            <input type="text" class="form-control" id="apellidoCliente" name="apellidoCliente" value="<?php echo htmlspecialchars($apellidoCliente); ?>" readonly>
        </div>
       
        <div class="form-group">
            <label for="buscarCedulaEmpleado">Cédula del Empleado:</label>
            <input type="text" class="form-control" id="buscarCedulaEmpleado" name="buscarCedulaEmpleado" placeholder="Ingrese la cédula del empleado" value="<?php echo isset($buscarCedulaEmpleado) ? htmlspecialchars($buscarCedulaEmpleado) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="nombreEmpleado">Nombre Empleado:</label>
            <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="apellidoEmpleado">Apellido Empleado:</label>
            <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado" value="<?php echo htmlspecialchars($apellidoEmpleado); ?>" readonly>
        </div>
        <hr> <!-- Separador para claridad -->
        <div class="form-group">
            <label for="idTractorSeleccionado">Seleccionar Tractor:</label>
            <select class="form-control" id="idTractorSeleccionado" name="idTractorSeleccionado">
                <option value="">Seleccione un tractor...</option>
                <?php foreach ($tractoresDisponibles as $tractor): ?>
                    <option value="<?php echo $tractor['tractorid']; ?>" <?php echo isset($idTractorSeleccionado) && $idTractorSeleccionado == $tractor['tractorid'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tractor['marca'] . ' ' . $tractor['modelo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fechaInicio">Fecha de Inicio:</label>
            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo isset($fechaInicio) ? htmlspecialchars($fechaInicio) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="fechaFin">Fecha de Fin:</label>
            <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="<?php echo isset($fechaFin) ? htmlspecialchars($fechaFin) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="precioPorDia">Precio Unitario por Día:</label>
            <input type="number" class="form-control" id="precioPorDia" name="precioPorDia" min="0" step="0.01" value="<?php echo isset($precioPorDia) ? htmlspecialchars($precioPorDia) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad de Tractores:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="1">
        </div>
        <div class="form-group">
            <label for="totalAlquiler">Total Alquiler:</label>
            <input type="text" class="form-control" id="totalAlquiler" name="totalAlquiler" value="<?php echo htmlspecialchars(number_format($totalAlquiler, 2)); ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary" name="realizarAlquiler">Realizar Alquiler</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
