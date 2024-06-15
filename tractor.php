<?php
// Conexión a la base de datos
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

// Función para obtener todos los modelos de tractores
function obtenerModelosTractores($db) {
    $query = $db->query("SELECT * FROM ModelosTractores ORDER BY Modelo");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener todos los proveedores
function obtenerProveedores($db) {
    $query = $db->query("SELECT * FROM Proveedores ORDER BY ProveedorID ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


// Función para obtener todos los tractores con las imágenes en base64
function obtenerTractores($db) {
    $query = $db->query("SELECT T.*, M.Marca, M.Modelo, encode(T.Imagen, 'base64') as ImagenBase64 FROM Tractores T INNER JOIN ModelosTractores M ON T.ModeloID = M.ModeloID ORDER BY T.TractorID ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener los tractores
$tractores = obtenerTractores($db);


// Obtener los tractores
$tractores = obtenerTractores($db);

// Manejar operaciones CRUD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Agregar tractor
    if (isset($_POST["agregarTractor"])) {
        $modeloID = $_POST["modelo"];
        $imagen = $_FILES["imagen"];
        $año = $_POST["año"];
        $estado = $_POST["estado"];
        $proveedorID = $_POST["proveedor"];
        $cantidad = $_POST["cantidad"];
        $precioCompra = $_POST["precioCompra"];

        // Manejo de la imagen (carga y almacenamiento)
        $imagenData = file_get_contents($imagen['tmp_name']);

        // Obtener el precio de compra ingresado
$precioCompra = $_POST["precioCompra"];

// Calcular el precio unitario para los clientes (precio de compra + 15%)
$precioUnitario = $precioCompra * 1.15;

// Insertar el nuevo tractor en la tabla Tractores
$query = $db->prepare("INSERT INTO Tractores (ModeloID, Imagen, Año, Estado) VALUES (?, ?, ?, ?)");
$query->bindParam(1, $modeloID, PDO::PARAM_INT);
$query->bindParam(2, $imagenData, PDO::PARAM_LOB);
$query->bindParam(3, $año, PDO::PARAM_INT);
$query->bindParam(4, $estado, PDO::PARAM_STR);
$query->execute();
$tractorID = $db->lastInsertId();

// Insertar el nuevo registro en la tabla Inventario
$query = $db->prepare("INSERT INTO Inventario (TractorID, ProveedorID, FechaIngreso, Cantidad, PrecioCompra, PrecioUnitario) VALUES (?, ?, CURRENT_DATE, ?, ?, ?)");
$query->execute([$tractorID, $proveedorID, $cantidad, $precioCompra, $precioUnitario]);


        // Redirigir o mostrar mensaje de éxito
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    // Eliminar tractor
    if (isset($_POST['eliminarTractor'])) {
        $tractorID = $_POST['tractorID'];

        // Eliminar el tractor de la tabla Inventario
        $query = $db->prepare("DELETE FROM Inventario WHERE TractorID = ?");
        $query->execute([$tractorID]);

        // Eliminar el tractor de la tabla Tractores
        $query = $db->prepare("DELETE FROM Tractores WHERE TractorID = ?");
        $query->execute([$tractorID]);

        // Redirigir o mostrar mensaje de éxito
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concesionario de Tractores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilo personalizado */
        body {
            padding-left: 20%;
            padding-top: 50px; /* Ajusta el contenido para evitar que se superponga al nav */
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
        .table-img {
            width: 100px; /* Ajusta el tamaño de las imágenes en la tabla */
            height: auto; /* Mantén la proporción de la imagen */
        }
    </style>
</head>
<body>
<div class="sidenav" id="mySidenav">
    <a href="index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
    <a href="Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
    <a href="Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
    <a href="Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
    <a href="tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
    <a href="ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
    <a href="alquileres.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
    <a href="mantenimientos.php"><i class="fas fa-tools mr-2"></i> Mantenimiento</a>
    <a href="facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
    <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
    <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
</div>
<div class="container mt-5">
    <h1>Concesionario de Tractores</h1>
    
    <h2>Agregar Nuevo Tractor</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="agregarTractor" value="1">
        
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="modelo">Modelo de Tractor:</label>
                <select class="form-control" name="modelo" id="modelo" required>
                    <?php
                    $modelos = obtenerModelosTractores($db);
                    if (!$modelos) {
                        echo "<option>No hay modelos disponibles</option>";
                    } else {
                        foreach ($modelos as $modelo) {
                            echo "<option value='{$modelo['modeloid']}'>{$modelo['marca']} - {$modelo['modelo']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="proveedor">Proveedor:</label>
                <select class="form-control" name="proveedor" id="proveedor" required>
                    <?php
                    $proveedores = obtenerProveedores($db);
                    if (!$proveedores) {
                        echo "<option>No hay proveedores disponibles</option>";
                    } else {
                        foreach ($proveedores as $proveedor) {
                            echo "<option value='{$proveedor['proveedorid']}'>{$proveedor['nombre']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="año">Año:</label>
                <input type="number" class="form-control" name="año" id="año" min="1900" max="<?php echo date("Y"); ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="estado">Estado:</label>
                <select class="form-control" name="estado" id="estado" required>
                    <option value="disponible">Disponible</option>
                    <option value="vendido">Vendido</option>
                    <option value="alquilado">Alquilado</option>
                </select>
            </div>
    
            <div class="form-group col-md-4">
    <label for="precioUnitario">Precio Unitario (sin IVA):</label>
    <input type="number" class="form-control" name="precioUnitario" id="precioUnitario" step="0.01" min="0" required>
</div>

<div class="form-group col-md-4">
    <label for="cantidad">Cantidad:</label>
    <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" required onchange="calcularPrecioCompra()">
</div>

<div class="form-group col-md-4">
    <label for="precioCompra">Precio de Compra:</label>
    <input type="number" class="form-control" name="precioCompra" id="precioCompra" step="0.01" min="0" readonly>
</div>
<script>
    function calcularPrecioCompra() {
        var precioUnitario = parseFloat(document.getElementById('precioUnitario').value);
        var cantidad = parseInt(document.getElementById('cantidad').value);

        if (!isNaN(precioUnitario) && !isNaN(cantidad) && cantidad > 0) {
            var precioCompra = precioUnitario * cantidad;
            document.getElementById('precioCompra').value = precioCompra.toFixed(2);
        }
    }
</script>


        </div>
        
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" name="imagen" id="imagen" required>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Tractor</button>
    </form>
</div>


 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Año</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tractores as $tractor): ?>
            <tr>
                <td><?php echo $tractor['tractorid']; ?></td>
                <td><?php echo $tractor['modelo']; ?></td>
                <td><?php echo $tractor['marca']; ?></td>
                <td><?php echo $tractor['año']; ?></td>
                <td><?php echo $tractor['estado']; ?></td>
                <td>
                    <img src="data:image/jpeg;base64,<?php echo $tractor['imagenbase64']; ?>" class="table-img">
                </td>
                <td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display:inline;">
                        <input type="hidden" name="tractorID" value="<?php echo $tractor['tractorid']; ?>">
                        <button type="submit" name="eliminarTractor" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                    <a href="editar_tractor.php?tractorID=<?php echo $tractor['tractorid']; ?>" class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Scripts de Bootstrap y Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>
