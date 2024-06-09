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
    die("ID de empleado no proporcionado.");
}

$empleadoID = $_GET['id'];

// Obtener los detalles del empleado
$query = $db->prepare("SELECT * FROM Empleados WHERE empleadoid = ?");
$query->execute([$empleadoID]);
$empleado = $query->fetch(PDO::FETCH_ASSOC);

// Verificar si el empleado existe
if (!$empleado) {
    die("Empleado no encontrado.");
}

// Actualizar el empleado si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $puesto = $_POST["puesto"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    $query = $db->prepare("UPDATE Empleados SET nombre = ?, apellido = ?, puesto = ?, teléfono = ?, email = ? WHERE empleadoid = ?");
    $query->execute([$nombre, $apellido, $puesto, $telefono, $email, $empleadoID]);

    // Redirigir a la página principal después de la actualización
    header("Location: empleados.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Editar Empleado</h2>
    <form method="post">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $empleado['apellido']; ?>" required>
        </div>
        <div class="form-group">
            <label for="puesto">Puesto:</label>
            <input type="text" class="form-control" id="puesto" name="puesto" value="<?php echo $empleado['puesto']; ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $empleado['teléfono']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $empleado['email']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
    </form>
</div>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
