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

// Función para obtener todos los proveedores ordenados por ID descendente
function obtenerProveedores($db) {
    $query = $db->query("SELECT * FROM Proveedores ORDER BY ProveedorID ASC");
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Función para generar enlace de edición
function generarEnlaceEditar($proveedorID) {
    return "<a href='editar_proveedor.php?id=$proveedorID'><i class='fas fa-edit'></i> Editar</a>";
}

// Función para generar enlace de eliminación
function generarEnlaceEliminar($proveedorID) {
    return "<a href='eliminar_proveedor.php?id=$proveedorID' onclick='return confirm(\"¿Estás seguro de eliminar este proveedor?\")'><i class='fas fa-trash'></i> Eliminar</a>";
}

// Mostrar tabla de proveedores
function mostrarProveedores($proveedores) {
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

    // Preparar y ejecutar la consulta SQL
    $query = $db->prepare("INSERT INTO Proveedores (nombre, dirección, teléfono, email) VALUES (?, ?, ?, ?)");
    $query->execute([$nombre, $direccion, $telefono, $email]);

    // Recargar la página para actualizar la tabla
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Agregar Nuevo Proveedor</h2>
    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
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
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Proveedor</button>
    </form>
</div>

<div class="container
mt-4">
    <h2>Lista de Proveedores</h2>
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
