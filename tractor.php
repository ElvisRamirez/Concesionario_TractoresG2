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

// Función para obtener todos los tractores
function obtenerTractores($db) {
    $query = $db->query("SELECT T.*, M.Marca, M.Modelo FROM Tractores T INNER JOIN ModelosTractores M ON T.ModeloID = M.ModeloID ORDER BY T.TractorID ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

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

        // Insertar el nuevo tractor en la tabla Tractores
        $query = $db->prepare("INSERT INTO Tractores (ModeloID, Imagen, Año, Estado) VALUES (?, ?, ?, ?)");
        $query->bindParam(1, $modeloID, PDO::PARAM_INT);
        $query->bindParam(2, $imagenData, PDO::PARAM_LOB);
        $query->bindParam(3, $año, PDO::PARAM_INT);
        $query->bindParam(4, $estado, PDO::PARAM_STR);
        $query->execute();
        $tractorID = $db->lastInsertId();

        // Insertar el nuevo registro en la tabla Inventario
        $query = $db->prepare("INSERT INTO Inventario (TractorID, ProveedorID, FechaIngreso, Cantidad, PrecioCompra) VALUES (?, ?, CURRENT_DATE, ?, ?)");
        $query->execute([$tractorID, $proveedorID, $cantidad, $precioCompra]);

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
</head>
<body>
    <div class="container mt-5">
        <h1>Concesionario de Tractores</h1>
        <h2>Lista de Tractores</h2>
        
        <h2>Agregar Nuevo Tractor</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="agregarTractor" value="1">
            <div class="form-group">
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
            <div class="form-group">
                <label for="imagen">Imagen:</label>
                <input type="file" class="form-control-file" name="imagen" id="imagen" required>
            </div>
            <div class="form-group">
                <label for="año">Año:</label>
                <input type="number" class="form-control" name="año" id="año" min="1900" max="<?php echo date("Y"); ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" name="estado" id="estado" required>
                    <option value="disponible">Disponible</option>
                    <option value="vendido">Vendido</option>
                    <option value="alquilado">Alquilado</option>
                </select>
            </div>
            <div class="form-group">
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
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" name="cantidad" id="cantidad" min="1" required>
            </div>
            <div class="form-group">
                <label for="precioCompra">Precio de Compra:</label>
                <input type="number" class="form-control" name="precioCompra" id="precioCompra" step="0.01" min="0" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Tractor</button>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Año</th>
                    <th>Estado</th>
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
