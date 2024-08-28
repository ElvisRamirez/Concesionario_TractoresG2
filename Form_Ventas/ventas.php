<?php
include "../conexion.php";


// Función para obtener el ID, nombre y apellido por cédula de cliente
function obtenerClientePorCedula($db, $cedula)
{
    $query = $db->prepare("SELECT ClienteID, Nombre, Apellido FROM Clientes WHERE Cedula = ?");
    $query->execute([$cedula]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener el ID, nombre y apellido por cédula de empleado
function obtenerEmpleadoPorCedula($db, $cedula)
{
    $query = $db->prepare("SELECT EmpleadoID, Nombre, Apellido FROM Empleados WHERE Cedula = ?");
    $query->execute([$cedula]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Función para obtener tractores disponibles con modelo, marca y precio unitario
function obtenerTractoresDisponibles($db)
{
    try {
        $query = $db->prepare("
            SELECT t.TractorID, m.Marca, m.Modelo, i.PrecioUnitario, i.Cantidad AS CantidadDisponible
            FROM Tractores t
            INNER JOIN ModelosTractores m ON t.ModeloID = m.ModeloID
            INNER JOIN Inventario i ON t.TractorID = i.TractorID
            WHERE t.Estado = 'disponible'
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No tienes permisos para ver las Ventas!',
                        willClose: () => {
                            window.location.href = 'http://localhost:3000/index.php'; // Cambia '/' por la URL de tu página principal
                        }
                    });
                });
              </script>";
        return [];
    }
}
// Función para realizar una venta y actualizar el inventario
function realizarVenta($db, $clienteID, $empleadoID, $tractorID, $cantidad, $precioUnitario)
{
    try {
        // Iniciar transacción
        $db->beginTransaction();

        // Insertar en tabla Facturas
        $queryVenta = $db->prepare("
            INSERT INTO Facturas (ClienteID, EmpleadoID, FechaFactura, TotalFactura) 
            VALUES (?, ?, CURRENT_DATE, ?)
            RETURNING FacturaID
        ");
        $totalVenta = $cantidad * $precioUnitario;
        $queryVenta->execute([$clienteID, $empleadoID, $totalVenta]);
        $facturaID = $queryVenta->fetchColumn();

        // Obtener la descripción del tractor vendido desde la tabla ModelosTractores
        $queryDescripcion = $db->prepare("
SELECT Marca, Modelo
FROM ModelosTractores
WHERE ModeloID = (
    SELECT ModeloID
    FROM Tractores
    WHERE TractorID = ?
)
");
        $queryDescripcion->execute([$tractorID]);
        $tractor = $queryDescripcion->fetch(PDO::FETCH_ASSOC);

        // Verificar si las claves 'Marca' y 'Modelo' están definidas en $tractor
        if (isset($tractor['Marca']) && isset($tractor['Modelo'])) {
            $descripcion = $tractor['Marca'] . ' ' . $tractor['Modelo'];
        } else {
            // Manejar la situación donde las claves no están definidas
            $descripcion = 'Descripción no disponible';
        }

        // Pasar la descripción (o usarla según sea necesario)
        echo $descripcion;


        // Insertar en tabla DetallesFactura
        $queryDetalleFactura = $db->prepare("
            INSERT INTO DetallesFactura (FacturaID, Descripcion, PrecioUnitario, Cantidad) 
            VALUES (?, ?, ?, ?)
        ");
        $queryDetalleFactura->execute([$facturaID, $descripcion, $precioUnitario, $cantidad]);

        // Actualizar inventario
        $queryActualizarInventario = $db->prepare("
            UPDATE Inventario 
            SET Cantidad = Cantidad - ? 
            WHERE TractorID = ?
        ");
        $queryActualizarInventario->execute([$cantidad, $tractorID]);

        // Confirmar transacción
        $db->commit();
        return $facturaID; // Devolver el ID de la factura para su uso posterior
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $db->rollBack();
        throw $e;
    }
}

// Procesar el formulario cuando se envía
$nombreCliente = "";
$apellidoCliente = "";
$nombreEmpleado = "";
$apellidoEmpleado = "";
$tractoresDisponibles = [];
$precioUnitario = 0.00;
$cantidad = 1;
$totalVenta = 0.00;
$mensajeError = "";
$mensajePago = "";
$cantidadDisponible = 0.00;

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

        // Obtener el precio unitario del tractor seleccionado
        foreach ($tractoresDisponibles as $tractor) {
            if ($tractor['tractorid'] == $idTractorSeleccionado) {
                $precioUnitario = $tractor['preciounitario'];
                $cantidadDisponible = $tractor['cantidaddisponible'];
                break;
            }
        }

        if (isset($_POST["cantidad"])) {
            $cantidad = $_POST["cantidad"];
            if ($cantidad > $cantidadDisponible) {
                $mensajeError = "No hay suficiente cantidad disponible para el tractor seleccionado.";
            } else {
                $totalVenta = $precioUnitario * $cantidad;
                if (isset($_POST["realizarVenta"])) {
                    if (!isset($clienteID) || !isset($empleadoID)) {
                        $mensajeError = "Debe seleccionar un cliente y un empleado válidos.";
                    } else {
                        try {
                            $facturaID = realizarVenta($db, $clienteID, $empleadoID, $idTractorSeleccionado, $cantidad, $precioUnitario);

                            // Registrar el pago
                            $montoPago = $totalVenta;
                            $fechaPago = date("Y-m-d");
                            $formaPago = $_POST["formaPago"];

                            $queryPago = $db->prepare("
                                INSERT INTO Pagos (FacturaID, FechaPago, MontoPago, FormaPago)
                                VALUES (?, ?, ?, ?)
                            ");
                            $queryPago->execute([$facturaID, $fechaPago, $montoPago, $formaPago]);

                            $mensajePago = "Pago registrado con éxito.";
                        } catch (PDOException $e) {
                            $mensajeError = "Error al realizar la venta: " . $e->getMessage();
                        }
                    }
                }
            }
        }
    }
} else {
    // Obtener la lista de tractores disponibles al cargar la página
    $tractoresDisponibles = obtenerTractoresDisponibles($db);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores - Realizar Venta</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>

    <div class="sidenav" id="mySidenav">
        <a href="#"><i class="fas fa-user mr-2"> </i><?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <a href="../index.php"><i class="fas fa-home mr-2"></i> Inicio</a>

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
            <a href="../Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'Administrador') : ?>
            <a href="../pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
            <a href="../alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
            <a href="../inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
        <?php endif; ?>

        <a href="../logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
    </div>


    <div class="wrapper">
        <div class="container mt-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center text-shadow">Realizar Nueva Venta</h2>
                    <?php if ($mensajeError) : ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
                    <?php endif; ?>
                    <?php if ($mensajePago) : ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($mensajePago); ?></div>
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
                        <hr>
                        <div class="form-group">
                            <label for="idTractorSeleccionado">Seleccionar Tractor:</label>
                            <select class="form-control" id="idTractorSeleccionado" name="idTractorSeleccionado" onchange="this.form.submit()">
                                <option value="">Seleccione un tractor...</option>
                                <?php foreach ($tractoresDisponibles as $tractor) : ?>
                                    <option value="<?php echo $tractor['tractorid']; ?>" <?php echo isset($idTractorSeleccionado) && $idTractorSeleccionado == $tractor['tractorid'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tractor['marca'] . ' ' . $tractor['modelo'] . ' - $' . number_format($tractor['preciounitario'], 2)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="precioUnitario">Precio Unitario:</label>
                            <input type="text" class="form-control" id="precioUnitario" name="precioUnitario" value="<?php echo htmlspecialchars(number_format($precioUnitario, 2)); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" min="1" onchange="this.form.submit()">
                        </div>
                        <div class="form-group">
                            <label for="formaPago">Forma de Pago:</label>
                            <select class="form-control" id="formaPago" name="formaPago" required>
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta">Tarjeta</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="totalVenta">Total Venta:</label>
                            <input type="text" class="form-control" id="totalVenta" name="totalVenta" value="<?php echo htmlspecialchars(number_format($totalVenta, 2)); ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-custom" name="realizarVenta">Realizar Venta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>