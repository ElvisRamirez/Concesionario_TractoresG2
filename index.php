<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú de Navegación Lateral con JavaScript</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Agregar la biblioteca de iconos de Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    /* Estilo personalizado */
    body {
      padding-top: 56px; /* Ajusta el contenido para evitar que se superponga al nav */
      overflow-x: hidden; /* Evita la barra de desplazamiento horizontal */
    }
    .sidenav {
      height: 100%;
      width: 250px;
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
     /* Estilo personalizado */
  .row-with-transition {
    overflow-x: hidden;
  }
  .row-with-transition:hover .row {
    transform: translateX(-235px); /* Ajusta el desplazamiento según tus necesidades */
  }
  .row {
    transition: transform 0.4s ease; /* Agrega una transición suave al desplazamiento */
  }
  </style>
</head>
<body>

<div class="sidenav" id="mySidenav">
        <a href="index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
        <a href="Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
        <a href="/Concesionario_Tractores/Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
        <a href="tractores.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="alquileres.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="mantenimientos.php"><i class="fas fa-tools mr-2"></i> Mantenimiento</a>
        <a href="facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
<div class="container-fluid content">
  <div class="presentation">
    <h2 class="mb-4">Bienvenido al Concesionario de Tractores</h2>
    <p class="mb-4">Somos líderes en la venta y servicio de tractores agrícolas.</p>
    <p class="mb-4">Contamos con una amplia gama de tractores de las mejores marcas del mercado.</p>
    <p class="mb-4">Nuestro equipo de expertos está listo para ayudarte a encontrar el tractor perfecto para tus necesidades.</p>
    <p class="mb-4">Visítanos hoy y descubre cómo podemos ayudarte a potenciar tu trabajo en el campo.</p>
    <img src="tractor.jpg" alt="Tractor en el campo" class="img-fluid rounded shadow">
  </div>


  <div class="mt-5 row-with-transition">
  <h2 class="mb-4">Últimos Productos</h2>
  <div class="row">
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow">
        <img class="card-img-top rounded" src="tractor1.jpg" alt="Tractor 1">
        <div class="card-body">
          <h5 class="card-title">Tractor Modelo A</h5>
          <p class="card-text">Descripción del tractor modelo A.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow">
        <img class="card-img-top rounded" src="tractor2.jpg" alt="Tractor 2">
        <div class="card-body">
          <h5 class="card-title">Tractor Modelo B</h5>
          <p class="card-text">Descripción del tractor modelo B.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow">
        <img class="card-img-top rounded" src="tractor3.jpg" alt="Tractor 3">
        <div class="card-body">
          <h5 class="card-title">Tractor Modelo C</h5>
          <p class="card-text">Descripción del tractor modelo C.</p>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="mt-5">
    <h2 class="mb-4">Servicios</h2>
    <ul class="list-unstyled">
      <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i>Venta de tractores nuevos y usados.</li>
      <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i>Servicio de mantenimiento y reparación.</li>
      <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i>Asesoramiento técnico y financiero.</li>
      <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i>Entrega a domicilio.</li>
    </ul>
  </div>
</div>

</body>
</html>