<?php

//=======
include "../conexion.php";

//>>>>>>> b964678eef722a98cc3f7c5f82fbdc9559e0064f
// Función para obtener todos los proveedores ordenados por ID descendente
function obtenerProveedores($db)
{
    try {
        $query = $db->query("SELECT * FROM Proveedores ORDER BY ProveedorID ASC");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No tienes permisos para ver los Proveedores!',
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
function generarEnlaceEditar($proveedorID)
{
    return "<a href='editar_proveedor.php?id=$proveedorID'><i class='fas fa-edit'></i> Editar</a>";
}

// Función para generar enlace de eliminación
function generarEnlaceEliminar($proveedorID)
{
    return "<a href='eliminar_proveedor.php?id=$proveedorID' onclick='return confirm(\"¿Estás seguro de eliminar este proveedor?\")'><i class='fas fa-trash'></i> Eliminar</a>";
}

// Mostrar tabla de proveedores
function mostrarProveedores($proveedores)
{
    echo "<table class='table'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    foreach ($proveedores as $proveedor) {
        echo "<tr>
                <td>{$proveedor['proveedorid']}</td>
                <td>{$proveedor['nombre']}</td>
                <td>{$proveedor['dirección']}</td>
                <td>{$proveedor['teléfono']}</td>
                <td>{$proveedor['email']}</td>
                <td>" . generarEnlaceEditar($proveedor['proveedorid']) . " | " . generarEnlaceEliminar($proveedor['proveedorid']) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
}

// Agregar nuevo proveedor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];



    try {
        $query = $db->prepare("INSERT INTO Proveedores (nombre, dirección, teléfono, email) VALUES (?, ?, ?, ?)");
        $query->execute([$nombre, $direccion, $telefono, $email]);

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
    <title>Proveedores</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
    /* Estilo personalizado */
    body {
        padding-left: 15%;
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
            <a href="../alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
            <a href="../Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'Administrador') : ?>
            <a href="../pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
            <a href="../inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
        <?php endif; ?>

        <a href="../logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="container">
                <h3 class="card-title text-center text-shadow">Agregar Nuevo Proveedor</h3>
                <form method="post">
                    <div class="form-group">

                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">

                        <input type="text" class="form-control" id="direccion" placeholder="Dirección" name="direccion">
                    </div>
                    <div class="form-group">

                        <input type="text" class="form-control" id="telefono" placeholder="Teléfono" name="telefono" required>
                    </div>
                    <div class="form-group">

                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-custom"><i class="fas fa-plus"></i> Agregar Proveedor</button>
                </form>
            </div>
        </div>
    </div>


    <div class="container
mt-3 ">
        <h2 class="text-shadow text-white">Lista de Proveedores</h2>
        <?php
        $proveedores = obtenerProveedores($db);
        mostrarProveedores($proveedores);
        ?>
    </div>

    <!-- Scripts de Bootstrap y Font Awesome -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>