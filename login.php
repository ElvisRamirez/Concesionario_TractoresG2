<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,600,0,0" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    * {
        box-sizing:
            border-box;
        margin: 0;
        padding: 0;
    }

    html,
    body {
        height: 100%;
        width: 100%;
    }

    body {
        display: flex;
        align-items: center;
        justify-content:
            center;
        background-size: cover;
        font-family: 'Euclid Circular A', sans-serif;
        height: 100vh;
    }

    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: 1200px;
    }

    .login-container {
        width: 90%;
        max-width: 380px;
        padding: 40px 30px;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.9);
        color: #161616;
        text-align: center;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
        font-size: 36px;
        font-weight: 500;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
        position: relative;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        height: 50px;
        padding: 0 20px 0 15px;
        font-size: 16px;
        border: 2px solid #ced4da;
        border-radius: 8px;
        background: transparent;
        color: #161616;
        outline: none;
    }

    .form-control:focus {
        border-color: #367c2b;
    }

    .btn-primary {
        width: 100%;
        height: 50px;
        background-color: #367c2b;
        border: none;
        color: #f9f9f9;
        font-weight: 600;
        letter-spacing: 2px;
        cursor: pointer;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background-color: #2e6c28;
    }

    .forgot-credentials {
        display: block;
        margin-top: 12px;
        color: #367c2b;
        font-size: 16px;
        text-decoration: none;
    }

    .forgot-credentials:hover {
        text-decoration: underline;
    }

    .tractor-image {
        margin-right: 50px;
        margin-left: 10px;
        width: 50%;
        height: auto;
        max-width: 600px;
    }

    /* Password Toggle Styles */
    .password-container {
        display: flex;
        align-items: center;
    }
</style>
</head>

<body>
    <div class="login-wrapper">
        <img src="https://www.deere.com/assets/images/region-3/products/tractors/heavy-tractors/tractor-8270r-estudio.png" alt="Tractor Image" class="tractor-image">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <form action="login_process.php" method="post" class="login-form">
                <div class="form-group">
                    <label for="username"><i class="fas fa-user"></i> Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>

        </div>
    </div>
    <!-- Scripts de Bootstrap -->
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>