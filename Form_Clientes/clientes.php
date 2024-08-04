<?php

include "../conexion.php";
include "../permisos.php";

// Función para obtener todos los clientes
function obtenerClientes($db)
{
    try {
        $query = $db->query("SELECT * FROM Clientes");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No tienes permisos para ver los Clientes!',
                        willClose: () => {
                            window.location.href = 'http://localhost:3000/index.php'; // Cambia '/' por la URL de tu página principal
                        }
                    });
                });
              </script>";
        return [];
    }
}

// Función para generar enlace de edición
function generarEnlaceEditar($clienteID)
{
    return "<a href='editar_cliente.php?id=$clienteID'><i class='fas fa-edit'></i> Editar</a>";
}

// Función para generar enlace de eliminación
function generarEnlaceEliminar($clienteID)
{
    return "<a href='eliminar_cliente.php?id=$clienteID' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")'><i class='fas fa-trash'></i> Eliminar</a>";
}

// Mostrar tabla de clientes
function mostrarClientes($clientes)
{
    if (empty($clientes)) {
        echo "<p>No tienes permisos para ver esta información.</p>";
    } else {
        echo "<table class='table table-sm'>
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
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $cedula = $_POST["cedula"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    try {
        $query = $db->prepare("INSERT INTO Clientes (nombre, apellido, cedula, dirección, teléfono, email) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([$nombre, $apellido, $cedula, $direccion, $telefono, $email]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'NO TIENES LOS PERMISOS PARA ESTA ACCION!',
                    });
                });
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
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
    </style>
</head>

<body>

    <div class="sidenav" id="mySidenav">
        <a href="#"><i class="fas fa-user mr-2"> </i><?php echo htmlspecialchars($username); ?></a>
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
        <a href="../logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
    </div>

    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <h3 class="card-title text-center text-shadow">Agregar Nuevo Cliente</h3>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Cédula" pattern="[0-9]{1,10}" maxlength="10" title="La cédula debe contener entre 1 y 10 dígitos numéricos" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" pattern="[0-9]{1,10}" maxlength="10" title="El teléfono debe contener entre 1 y 10 dígitos numéricos" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <button type="submit" class="btn btn-custom"><i class="fas fa-plus"></i> Agregar Cliente</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="container ">
        <h2 class="text-shadow text-white">Lista de Clientes</h2>
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