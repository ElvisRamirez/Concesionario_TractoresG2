<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #f5c6cb;
            background-color: #f8d7da;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #fff;
            background-color: #721c24;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Mostrar mensaje de error basado en el parámetro
        $errorType = $_GET['error'] ?? 'general';

        if ($errorType === 'permissions') {
            echo '<h1>Error de permisos</h1>';
            echo '<p>No tienes permiso para acceder a esta operación. Por favor, contacta al administrador si crees que esto es un error.</p>';
        } else {
            echo '<h1>Ha ocurrido un error</h1>';
            echo '<p>Se ha producido un error al intentar realizar la operación. Por favor, inténtelo de nuevo más tarde.</p>';
        }
        ?>
        <a href="javascript:window.history.back();" class="button">Volver</a>
    </div>
</body>
</html>
