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

// Verificar si se ha enviado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de proveedor no proporcionado.");
}

$proveedorID = $_GET['id'];

// Obtener los detalles del proveedor
$query = $db->prepare("SELECT * FROM Proveedores WHERE ProveedorID = ?");
$query->execute([$proveedorID]);
$proveedor = $query->fetch(PDO::FETCH_ASSOC);

// Verificar si el proveedor existe
if (!$proveedor) {
    die("Proveedor no encontrado.");
}

// Actualizar el proveedor si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    $query = $db->prepare("UPDATE Proveedores SET Nombre = ?, Dirección = ?, Teléfono = ?, Email = ? WHERE ProveedorID = ?");
    $query->execute([$nombre, $direccion, $telefono, $email, $proveedorID]);

    // Redirigir a la página principal después de la actualización
    header("Location:proveedores.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proveedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Proveedor</h2>
    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($proveedor['nombre']) ? $proveedor['nombre'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo isset($proveedor['dirección']) ? $proveedor['dirección'] : ''; ?>">
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo isset($proveedor['teléfono']) ? $proveedor['teléfono'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($proveedor['email']) ? $proveedor['email'] : ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
