<?php
include "conexion.php";
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
function obtenerTractoresDisponibles($db)
{
    $query = $db->prepare("
        SELECT TractorID, Marca, Modelo
        FROM VistaTractoresDisponibles
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }

        /* Estilo personalizado */
        body {
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

        .bg-brown {
            background-color: #8B4513;
            /* Color café */

        }



        .btn-custom {
            background-color: #ff9800;
            /* Naranja */
            border-color: #ff9800;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #e68900;
            /* Naranja oscuro */
            border-color: #e68900;
            box-shadow: 2 5px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .text-shadow {
            color: black;
            text-shadow: 0 4px 8px rgba(255, 255, 255, 2);
        }

        .text-shadow1 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
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

    <div class="container">
        <h2 class="mb-4 text-white text-shadow1">Realizar Nuevo Alquiler</h2>
        <?php if (!empty($mensajeError)) : ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
        <?php endif; ?>
        <?php if (!empty($mensajeAlquiler)) : ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($mensajeAlquiler); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="row">
                <div class="col-md-4 ">
                    <div class="form-group ">
                        <label for="buscarCedulaCliente" class="text-shadow">Cédula del Cliente:</label>
                        <input type="text" class="form-control" id="buscarCedulaCliente" name="buscarCedulaCliente" placeholder="Ingrese la cédula del cliente" value="<?php echo isset($_POST['buscarCedulaCliente']) ? htmlspecialchars($_POST['buscarCedulaCliente']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nombreCliente" class="text-shadow">Nombre Cliente:</label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" value="<?php echo htmlspecialchars($nombreCliente); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="apellidoCliente" class="text-shadow">Apellido Cliente:</label>
                        <input type="text" class="form-control" id="apellidoCliente" name="apellidoCliente" value="<?php echo htmlspecialchars($apellidoCliente); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="buscarCedulaEmpleado" class="text-shadow">Cédula del Empleado:</label>
                        <input type="text" class="form-control" id="buscarCedulaEmpleado" name="buscarCedulaEmpleado" placeholder="Ingrese la cédula del empleado" value="<?php echo isset($_POST['buscarCedulaEmpleado']) ? htmlspecialchars($_POST['buscarCedulaEmpleado']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="nombreEmpleado" class="text-shadow">Nombre Empleado:</label>
                        <input type="text" class="form-control" id="nombreEmpleado" name="nombreEmpleado" value="<?php echo htmlspecialchars($nombreEmpleado); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="apellidoEmpleado" class="text-shadow">Apellido Empleado:</label>
                        <input type="text" class="form-control" id="apellidoEmpleado" name="apellidoEmpleado" value="<?php echo htmlspecialchars($apellidoEmpleado); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="idTractorSeleccionado" class="text-shadow">Seleccionar Tractor:</label>
                        <select class="form-control" id="idTractorSeleccionado" name="idTractorSeleccionado">
                            <option value="">Seleccione un tractor...</option>
                            <?php foreach ($tractoresDisponibles as $tractor) : ?>
                                <option value="<?php echo $tractor['tractorid']; ?>" <?php echo isset($_POST['idTractorSeleccionado']) && $_POST['idTractorSeleccionado'] == $tractor['tractorid'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tractor['marca'] . ' ' . $tractor['modelo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fechaInicio" class="text-shadow">Fecha de Inicio:</label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="<?php echo isset($_POST['fechaInicio']) ? htmlspecialchars($_POST['fechaInicio']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="fechaFin" class="text-shadow">Fecha de Fin:</label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="<?php echo isset($_POST['fechaFin']) ? htmlspecialchars($_POST['fechaFin']) : ''; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cantidad" class="text-shadow">Cantidad de Tractores:</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="<?php echo htmlspecialchars($cantidad); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="precioPorDia" class="text-shadow">Precio por Día:</label>
                        <input type="number" step="0.01" class="form-control" id="precioPorDia" name="precioPorDia" value="<?php echo htmlspecialchars($precioPorDia); ?>">
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group te">
                        <label for="totalAlquiler " class="text-shadow">Total Alquiler:</label>
                        <input type="text" class="form-control" id="totalAlquiler" name="totalAlquiler" value="<?php echo htmlspecialchars(number_format($totalAlquiler, 2)); ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-custom" name="realizarAlquiler">Realizar Alquiler</button>
                </div>
            </div>
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