<?php
include_once "conexion.php";

// Establece la sesión del usuario
// session_start();
$username = $_SESSION['username'] ?? null;

if ($username === null) {
    echo "Usuario no autenticado.";
    exit;
}

// Función para obtener los roles del usuario desde la base de datos
function obtenerRolesUsuario($db, $username)
{
    $roles = [];

    // Utiliza una consulta preparada con parámetros
    $query = $db->prepare("
        SELECT r.rolname
        FROM pg_roles r
        JOIN pg_auth_members m ON r.oid = m.roleid
        JOIN pg_user u ON u.usesysid = m.member
        WHERE u.usename = :username
    ");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['rolname'];
    }

    return $roles;
}


// Obtener los roles del usuario
$rolesUsuario = obtenerRolesUsuario($db, $username);

// Ejemplo de verificación de permisos
if (!in_array('Administrador', $rolesUsuario)) {
    echo "<div class='permiso-denegado-overlay'>No tienes permiso para realizar esta acción.</div>";
    exit;
}

// Código para usuarios autorizados
echo "Bienvenido, tienes los permisos necesarios.";
