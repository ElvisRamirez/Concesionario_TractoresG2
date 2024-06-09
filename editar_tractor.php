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
if (!isset($_GET['tractorID']) || empty($_GET['tractorID'])) {
    die("ID de tractor no proporcionado.");
}

$tractorID = $_GET['tractorID'];

// Obtener los detalles del tractor
$query = $db->prepare("SELECT T.*, M.Marca, M.Modelo FROM Tractores T INNER JOIN ModelosTractores M ON T.ModeloID = M.ModeloID WHERE TractorID = ?");
$query->execute([$tractorID]);
$tractor = $query->fetch(PDO::FETCH_ASSOC);

// Verificar si el tractor existe
if (!$tractor) {
    die("Tractor no encontrado.");
}

// Obtener todos los modelos de tractores
$queryModelos = $db->query("SELECT * FROM ModelosTractores");
$modelos = $queryModelos->fetchAll(PDO::FETCH_ASSOC);

// Inicializar variables para evitar los warnings
$modeloID = isset($tractor['ModeloID']) ? $tractor['ModeloID'] : "";
$año = isset($tractor['Año']) ? $tractor['Año'] : "";
$estado = isset($tractor['Estado']) ? $tractor['Estado'] : "";

// Manejar el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modeloID = $_POST["modeloID"];
    $año = $_POST["año"];
    $estado = $_POST["estado"];

    // Actualizar el tractor en la base de datos
    $query = $db->prepare("UPDATE Tractores SET ModeloID = ?, Año = ?, Estado = ? WHERE TractorID = ?");
    $query->execute([$modeloID, $año, $estado, $tractorID]);

    // Redirigir a la página principal después de la actualización
    header("Location:tractores.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tractor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Tractor</h1>
        <form method="post">
            <div class="form-group">
                <label for="modeloID">Modelo:</label>
                <select class="form-control" id="modeloID" name="modeloID" required>
                    <?php foreach ($modelos as $modelo): ?>
                        <option value="<?php echo $modelo['ModeloID']; ?>" <?php if ($modelo['ModeloID'] == $modeloID) echo 'selected'; ?>><?php echo $modelo['Marca'] . ' - ' . $modelo['Modelo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="año">Año:</label>
                <input type="number" class="form-control" id="año" name="año" value="<?php echo $año; ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="disponible" <?php if ($estado == 'disponible') echo 'selected'; ?>>Disponible</option>
                    <option value="vendido" <?php if ($estado == 'vendido') echo 'selected'; ?>>Vendido</option>
                    <option value="alquilado" <?php if ($estado == 'alquilado') echo 'selected'; ?>>Alquilado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </
