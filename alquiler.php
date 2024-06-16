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

$mensajeError = ""; // Variable para almacenar mensajes de error
$mensajeAlquiler = ""; // Variable para almacenar mensajes de éxito

$clienteID = null;
$empleadoID = null;
$nombreCliente = "";
$apellidoCliente = "";
$nombreEmpleado = "";
$apellidoEmpleado = "";
$tractoresDisponibles = [];
$totalAlquiler = 0.00;
$precioPorDia = 0.00; // Precio por día por defecto
$cantidad = 1; // Cantidad de tractores por defecto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["buscarCedulaCliente"])) {
        $buscarCedulaCliente = $_POST["buscarCedulaCliente"];
        // Lógica para buscar el cliente por cédula
        $queryCliente = $db->prepare("
            SELECT ClienteID, Nombre, Apellido
            FROM Clientes
            WHERE Cedula = :cedula
        ");
        $queryCliente->bindParam(":cedula", $buscarCedulaCliente);
        $queryCliente->execute();
        $cliente = $queryCliente->fetch(PDO::FETCH_ASSOC);

        if ($cliente) {
            $clienteID = $cliente['clienteid']; // Asignar el ClienteID encontrado
            $nombreCliente = $cliente['nombre'];
            $apellidoCliente = $cliente['apellido'];
        } else {
            $mensajeError = "Cliente no encontrado con la cédula proporcionada.";
        }
    }

    if (isset($_POST["buscarCedulaEmpleado"])) {
        $buscarCedulaEmpleado = $_POST["buscarCedulaEmpleado"];
        // Lógica para buscar el empleado por cédula
        $queryEmpleado = $db->prepare("
            SELECT EmpleadoID, Nombre, Apellido
            FROM Empleados
            WHERE Cedula = :cedula
        ");
        $queryEmpleado->bindParam(":cedula", $buscarCedulaEmpleado);
        $queryEmpleado->execute();
        $empleado = $queryEmpleado->fetch(PDO::FETCH_ASSOC);

        if ($empleado) {
            $empleadoID = $empleado['empleadoid']; // Asignar el EmpleadoID encontrado
            $nombreEmpleado = $empleado['nombre'];
            $apellidoEmpleado = $empleado['apellido'];
        } else {
            $mensajeError = "Empleado no encontrado con la cédula proporcionada.";
        }
    }

    // Obtener la lista de tractores disponibles
    $tractoresDisponibles = obtenerTractoresDisponibles($db);

    if (isset($_POST["idTractorSeleccionado"])) {
        $idTractorSeleccionado = $_POST["idTractorSeleccionado"];
        // Lógica para procesar el alquiler
        $fechaInicio = $_POST["fechaInicio"];
        $fechaFin = $_POST["fechaFin"];

        // Obtener el precio por día del formulario si está presente
        $precioPorDia = isset($_POST["precioPorDia"]) ? floatval($_POST["precioPorDia"]) : 0.00;

        // Calcular el total de alquiler solo si las fechas están completas
        if (!empty($fechaInicio) && !empty($fechaFin) && $precioPorDia > 0) {
            // Calcular días de alquiler
            $inicio = new DateTime($fechaInicio);
            $fin = new DateTime($fechaFin);
            $diferencia = $inicio->diff($fin);
            $diasAlquiler = $diferencia->days + 1; // Sumar 1 para incluir el último día

            // Obtener la cantidad de tractores seleccionada
            $cantidad = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 1;

            // Calcular total de alquiler
            $totalAlquiler = $diasAlquiler * $precioPorDia * $cantidad;

            // Insertar en la tabla Alquileres
            $insertAlquiler = $db->prepare("
                INSERT INTO Alquileres (ClienteID, EmpleadoID, FechaInicio, FechaFin, TotalAlquiler)
                VALUES (:clienteID, :empleadoID, :fechaInicio, :fechaFin, :totalAlquiler)
            ");

            $insertAlquiler->bindParam(":clienteID", $clienteID);
            $insertAlquiler->bindParam(":empleadoID", $empleadoID);
            $insertAlquiler->bindParam(":fechaInicio", $fechaInicio);
            $insertAlquiler->bindParam(":fechaFin", $fechaFin);
            $insertAlquiler->bindParam(":totalAlquiler", $totalAlquiler);

            try {
                $db->beginTransaction();

                // Insertar el registro de alquiler
                $insertAlquiler->execute();
                $alquilerID = $db->lastInsertId();

                // Insertar detalles del alquiler en DetallesAlquiler
                $insertDetalle = $db->prepare("
                    INSERT INTO DetallesAlquiler (AlquilerID, TractorID, PrecioUnitario, Cantidad)
                    VALUES (:alquilerID, :tractorID, :precioPorDia, :cantidad)
                ");

                $insertDetalle->bindParam(":alquilerID", $alquilerID);
                $insertDetalle->bindParam(":tractorID", $idTractorSeleccionado);
                $insertDetalle->bindParam(":precioPorDia", $precioPorDia);
                $insertDetalle->bindParam(":cantidad", $cantidad);

                $insertDetalle->execute();

                $db->commit();

                $mensajeAlquiler = "Alquiler registrado exitosamente.";
            } catch (PDOException $e) {
                $db->rollBack();
                $mensajeError = "Error al registrar el alquiler: " . $e->getMessage();
            }
        } else {
            $mensajeError = "Por favor complete las fechas y el precio por día correctamente.";
        }
    }
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores - Realizar Alquiler</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Realizar Nuevo Alquiler</h2>
    <?php if (!empty($mensajeError)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
    <?php endif; ?>
    <?php if (!empty($mensajeAlquiler)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensajeAlquiler); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="buscarCedulaCliente">Cédula del Cliente:</label>
            <input type="text" class="form-control" id="buscarCedulaCliente" name="buscarCedulaCliente" placeholder="Ingrese la cédula del cliente" value="<?php echo isset($_POST['buscarCedulaCliente']) ? htmlspecialchars($_POST['buscarCedulaCliente']) : ''; ?>">
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
            <input type="text" class="form-control" id="buscarCedulaEmpleado" name="buscarCedulaEmpleado" placeholder="Ingrese la cédula del empleado" value="<?php echo isset($_POST['buscarCedulaEmpleado']) ? htmlspecialchars($_POST['buscarCedulaEmpleado']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="nombreEmpleado">Nombre Empleado:</label>
            <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="apellidoEmpleado">Apellido Empleado:</label>
            <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado" value="<?php echo htmlspecialchars($apellidoEmpleado); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="idTractorSeleccionado">Seleccionar Tractor:</label>
            <select class="form-control" id="idTractorSeleccionado" name="idTractorSeleccionado">
                <option value="">Seleccione un tractor...</option>
                <?php foreach ($tractoresDisponibles as $tractor): ?>
                    <option value="<?php echo $tractor['tractorid']; ?>" <?php echo isset($_POST['idTractorSeleccionado']) && $_POST['idTractorSeleccionado'] == $tractor['tractorid'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tractor['marca'] . ' ' . $tractor['modelo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fechaInicio">Fecha de Inicio:</label>
            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo isset($_POST['fechaInicio']) ? htmlspecialchars($_POST['fechaInicio']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="fechaFin">Fecha de Fin:</label>
            <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="<?php echo isset($_POST['fechaFin']) ? htmlspecialchars($_POST['fechaFin']) : ''; ?>">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad de Tractores:</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="<?php echo htmlspecialchars($cantidad); ?>">
        </div>
        <div class="form-group">
            <label for="precioPorDia">Precio por Día:</label>
            <input type="number" step="0.01" class="form-control" id="precioPorDia" name="precioPorDia" value="<?php echo htmlspecialchars($precioPorDia); ?>">
        </div>
        <div class="form-group">
            <label for="totalAlquiler">Total Alquiler:</label>
            <input type="text" class="form-control" id="totalAlquiler" name="totalAlquiler" value="<?php echo htmlspecialchars(number_format($totalAlquiler, 2)); ?>" readonly>
        </div>
        <button type="submit" class="btn btn-primary" name="realizarAlquiler">Realizar Alquiler</button>
    </form>
</div>

<script>
    // Función para calcular y actualizar el total de alquiler
    function actualizarTotalAlquiler() {
        var fechaInicio = document.getElementById("fechaInicio").value;
        var fechaFin = document.getElementById("fechaFin").value;
        var precioPorDia = parseFloat(document.getElementById("precioPorDia").value);
        var cantidad = parseInt(document.getElementById("cantidad").value);

        if (fechaInicio && fechaFin && precioPorDia && cantidad) {
            var inicio = new Date(fechaInicio);
            var fin = new Date(fechaFin);
            var diferencia = (fin.getTime() - inicio.getTime()) / (1000 * 3600 * 24); // Diferencia en días

            if (diferencia >= 0) {
                var diasAlquiler = Math.floor(diferencia) + 1; // Sumar 1 para incluir el último día
                var totalAlquiler = diasAlquiler * precioPorDia * cantidad;
                document.getElementById("totalAlquiler").value = totalAlquiler.toFixed(2);
            } else {
                alert("La fecha de fin debe ser posterior o igual a la fecha de inicio.");
            }
        }
    }

    // Event listeners para los cambios relevantes
    document.getElementById("fechaInicio").addEventListener("change", actualizarTotalAlquiler);
    document.getElementById("fechaFin").addEventListener("change", actualizarTotalAlquiler);
    document.getElementById("precioPorDia").addEventListener("change", actualizarTotalAlquiler);
    document.getElementById("cantidad").addEventListener("change", actualizarTotalAlquiler);

    // Llamar a la función inicialmente para calcular el total si hay datos previos
    actualizarTotalAlquiler();
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
