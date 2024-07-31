<?php
//HOLA QUE HACE
// Conexión a la base de datos
//$dbHost = '10.241.0.57';
$dbHost = '192.168.10.10';


// Incluir el archivo de conexión
include 'conexion.php';
//>>>>>>> b964678eef722a98cc3f7c5f82fbdc9559e0064f

 // Consulta SQL para obtener los últimos tractores disponibles desde la vista
 $sql = "SELECT * FROM ultimos_tractores_disponibles";

 // Preparar y ejecutar la consulta
 $stmt = $db->query($sql);
 $tractores = $stmt->fetchAll(PDO::FETCH_ASSOC);
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitado'; // Valor por defecto si no está definido
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú de Navegación Lateral con JavaScript</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Agregar la biblioteca de iconos de Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    /* Estilo personalizado */
    body {
    
      overflow-x: hidden; /* Evita la barra de desplazamiento horizontal */
    }
    .sidenav {
    
      height: 100%;
      width: 200px;
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
      padding: 20px;
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
    .label-highlight {
      background-color: white;
      color: black;
      padding: 0.5em 1em;
      border-radius: 0.25em;
      box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      width: 25pc;
    }
    

  </style>
</head>
<body>

<div class="sidenav" id="mySidenav">
        <a  href="#"><i class="fas fa-user mr-2" >  </i><?php echo htmlspecialchars($username); ?></a>
        <a href="index.php"><i class="fas fa-home mr-2"></i> Inicio</a>
        <a href="Form_Clientes/clientes.php"><i class="fas fa-user mr-2"></i> Clientes</a>
        <a href="Form_Empleado/empleados.php"><i class="fas fa-user-tie mr-2"></i> Empleados</a>
        <a href="Form_Proveedores/proveedores.php"><i class="fas fa-box mr-2"></i> Proveedores</a>
        <a href="tractor.php"><i class="fas fa-tractor mr-2"></i> Tractores</a>
        <a href="Form_Ventas/ventas.php"><i class="fas fa-shopping-cart mr-2"></i> Ventas</a>
        <a href="alquiler.php"><i class="fas fa-calendar-alt mr-2"></i> Alquileres</a>
        <a href="Facturas.php"><i class="fas fa-file-invoice-dollar mr-2"></i> Facturas</a>
        <a href="pagos.php"><i class="fas fa-credit-card mr-2"></i> Pagos</a>
        <a href="inventario.php"><i class="fas fa-warehouse mr-2"></i> Inventario</a>
    </div>
    
<div class="container-fluid content">
  <div class="presentation">
    <h2 class="mb-4 text-left">Bienvenido al Concesionario de Tractores</h2>
    <div class="d-flex" >
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicadores -->
    <ul class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ul>
    
    <!-- Slideshow -->
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="imagenes/Tractor_carusel_1.jpg" alt="Imagen 1" width="1100" height="500">
        <div class="carousel-caption">
          <h3>New Holland T7.270</h3>
          <p>"Potencia y eficiencia en cada surco del campo."</p>
        </div>   
      </div>
      <div class="carousel-item">
        <img src="imagenes/Tractor_carusel_2.jpg" alt="Imagen 2" width="1100" height="500">
        <div class="carousel-caption">
          <h3>Massey Ferguson 8700 S</h3>
          <p>"La tecnología que impulsa la agricultura moderna."</p>
        </div>   
      </div>
      <div class="carousel-item">
        <img src="imagenes/Tractor_carusel_3.jpg" alt="Imagen 3" width="1100" height="500">
        <div class="carousel-caption">
          <h3>Kubota M7-171</h3>
          <p>"El aliado perfecto para la agricultura de hoy."</p>
        </div>   
      </div>
    </div>
    
    <!-- Controles Izquierda y Derecha -->
    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" data-slide="next">
      <span class="carousel-control-next-icon"></span>
    </a>
  </div>
</div>

    <p class="mb-4 ">Somos líderes en la venta y servicio de tractores agrícolas.</p>
    <p class="mb-4">Contamos con una amplia gama de tractores de las mejores marcas del mercado.</p>
    <p class="mb-4">Nuestro equipo de expertos está listo para ayudarte a encontrar el tractor perfecto para tus necesidades.</p>
    <p class="mb-4">Visítanos hoy y descubre cómo podemos ayudarte a potenciar tu trabajo en el campo.</p>
  </div>


  <div class="mt-5 row-with-transition">
  <h2 class="mb-4">Últimos Tractores Disponibles</h2>
  <div class="row">
    <?php foreach ($tractores as $tractor) { ?>
    <div class="col-md-4 mb-4">
      <div class="card h-100 border-0 shadow">
        <?php if (isset($tractor['imagen'])) { ?>
          <img class="card-img-top rounded" src="data:image/jpeg;base64,<?php echo base64_encode(stream_get_contents($tractor['imagen'])); ?>" alt="<?php echo isset($tractor['marca']) && isset($tractor['modelo']) ? $tractor['marca'] . ' ' . $tractor['modelo'] : ''; ?>">
        <?php } ?>
        <div class="card-body">
          <h5 class="card-title"><?php echo isset($tractor['marca']) && isset($tractor['modelo']) ? $tractor['marca'] . ' ' . $tractor['modelo'] : ''; ?></h5>
          <p class="card-text"><strong>Año:</strong> <?php echo isset($tractor['año']) ? $tractor['año'] : ''; ?></p>
          <p class="card-text"><strong>Estado:</strong> <?php echo isset($tractor['estado']) ? $tractor['estado'] : ''; ?></p>
          <p class="card-text"><?php echo isset($tractor['descripcióntractor']) ? $tractor['descripcióntractor'] : ''; ?></p>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>


  <div class="mt-5">
    <h2 class="mb-4">Servicios</h2>
    <ul class="list-unstyled">
      <li class="mb-2 label-highlight"><i class="fas fa-check-circle text-success mr-2"></i>Venta de tractores nuevos y usados.</li>
      <li class="mb-2 label-highlight"><i class="fas fa-check-circle text-success mr-2"></i>Servicio de mantenimiento y reparación.</li>
      <li class="mb-2 label-highlight"><i class="fas fa-check-circle text-success mr-2"></i>Asesoramiento técnico y financiero.</li>
      <li class="mb-2 label-highlight"><i class="fas fa-check-circle text-success mr-2"></i>Entrega a domicilio.</li>
    </ul>
  </div>
</div>

</body>
</html>