<?php
include_once "conexion.php";

// Establece las credenciales del usuario actual

// Conexión a la base de datos con las credenciales del usuario
$credencialesUsuario = $credentials[$_SESSION['username']] ?? null;
// Función para obtener los roles del usuario desde la base de datos
function obtenerRolesUsuario($db) {
    $roles = [];
    $username = $_SESSION['username'];
    
    $query = $db->prepare("
        SELECT rolname
        FROM pg_roles
        WHERE rolname IN (
            SELECT pg_roles.rolname
            FROM pg_roles
            JOIN pg_auth_members ON pg_roles.oid = pg_auth_members.roleid
            JOIN pg_user ON pg_user.usesysid = pg_auth_members.member
            WHERE pg_user.usename = :username
        )
    ");
    $query->execute(['username' => $username]);
    
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['rolname'];
    }
    
    return $roles;
}


// Verificar si el usuario tiene un rol específico
function tieneRol($rolesUsuario, $rolBuscado) {
    return in_array($rolBuscado, $rolesUsuario);
}

// Obtener los roles del usuario
$rolesUsuario = obtenerRolesUsuario($db);

// Ejemplo de verificación de permisos

if (!tieneRol($rolesUsuario, 'Administrador')) {
    echo "<div class='permiso-denegado-overlay'>No tienes permiso para realizar esta acción.</div>";
    exit;
}
?>
<