<?php
// Incluir la configuración de la base de datos
include 'config.php';

// Obtener los trayectos
$trayectos = obtenerTrayectos($link);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>La Criolla - Trayectos</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/crr.css">
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <img src="img/435393254_122138877350155838_5402950430373005889_n.jpg" width="40px" alt=""><a class="navbar-brand" href="#home">La Criolla</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#trayectos">Trayectos</a></li>
          <li class="nav-item"><a class="nav-link" href="#form">Contacto</a></li>
          <!-- Enlace a Facebook, como estaba en tu HTML -->
          <li class="nav-item"><a class="nav-link" href="#casa"> <a href="https://www.facebook.com/cfp61">
            <button><img src="img/889183.webp" width="25px" alt="Facebook"></button>
          </a> </a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- SECCIÓN HOME -->
  <section id="home" class="section-home">
    <div class="container text-center">
      <h1>Bienvenido a La Criolla</h1>
      <p id="jaja">Planificá tus desplazamientos con precisión. <br> Te brindamos trayectos confiables y actualizados para moverte con facilidad por la ciudad. <br></p>
    </div>
  </section>

  <!-- SECCIÓN TRAYECTOS / CARRUSEL (Cargado Dinámicamente) -->
  <section id="trayectos" class="section-trayectos">
    <div class="container">
      <h2>Trayectos Formativos</h2>
      <div class="carousel-container">
        <button class="nav-btn prev">❮</button>

        <div class="cards-wrapper" id="cardsWrapper">
          <?php
          if (count($trayectos) > 0):
            // Itera sobre los resultados de la base de datos
            foreach ($trayectos as $i => $trayecto):
          ?>
              <div class="card my-card">
                <!-- Se asume que las imágenes están en la carpeta 'img/' -->
                <img src="<?php echo htmlspecialchars($trayecto['imagen']); ?>" width="50px" class="card-img-top" alt="<?php echo htmlspecialchars($trayecto['titulo']); ?>">
                <div class="card-body">
                  <h5 class="card-title"><?php echo htmlspecialchars($trayecto['titulo']); ?></h5>
                  <!-- Se limita la descripción para no desbordar las tarjetas -->
                  <p class="card-text"><?php echo htmlspecialchars(substr($trayecto['descripcion'], 0, 150)) . (strlen($trayecto['descripcion']) > 150 ? '...' : ''); ?></p>
                  <!-- Enlace a una página de detalle con el ID del trayecto. -->
                  <a href="#page<?php echo $trayecto['id']; ?>" class="btn btn-primary">Ver más</a>
                </div>
              </div>
          <?php
            endforeach;
          else:
          ?>
             <p class="text-center w-100">No hay trayectos disponibles actualmente.</p>
          <?php
          endif;
          ?>

        </div>

        <button class="nav-btn next">❯</button>
      </div>
    </div>
  </section>
  
  <!-- Apartado de Acceso a Administración (Punto Clave) -->
  <section id="admin-access" class="section-admin-access text-center p-5">
    <a href="admin.php" class="btn btn-dark btn-lg">Acceso a Gestión de Trayectos</a>
  </section>

  <!-- SECCIÓN FORMULARIO (CONTACTO) -->
  <section id="form" class="section-form">
    <div class="container form-container">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13641.079786950097!2d-58.11756995781337!3d-31.268627448346017!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95adecae2de3210d%3A0xf7035fb22c8c2c8d!2sLa%20Criolla%2C%20Entre%20R%C3%ADos!5e0!3m2!1ses-419!2sar!4v1763255267824!5m2!1ses-419!2sar" id="map" loading="lazy"></iframe>
      <form class="contact-form">
        <h3>Contacto</h3>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" class="form-control" placeholder="Tu email">
        </div>
        <div class="mb-3">
          <label>Mensaje</label>
          <textarea class="form-control" rows="4" placeholder="Tu mensaje"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </form>
    </div>
  </section>


  <!-- Páginas de detalle estáticas, puedes generar estas dinámicamente si es necesario -->
  <?php foreach ($trayectos as $trayecto): ?>
    <section id="page<?php echo $trayecto['id']; ?>" class="paginablanca">
      <div class="container">
        <h1 class="display-4 text-center mb-4"><?php echo htmlspecialchars($trayecto['titulo']); ?></h1>
        <div class="row align-items-center">
          <div class="col-md-4">
             <img src="<?php echo htmlspecialchars($trayecto['imagen']); ?>" class="img-fluid rounded-3 shadow" alt="<?php echo htmlspecialchars($trayecto['titulo']); ?>">
          </div>
          <div class="col-md-8">
            <p class="lead"><?php echo nl2br(htmlspecialchars($trayecto['descripcion'])); ?></p>
            <a href="#trayectos" class="btn btn-outline-secondary mt-3">Volver a Trayectos</a>
          </div>
        </div>
      </div>
    </section>
  <?php endforeach; ?>


  <script src="./javascrpit/fun.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Cerrar la conexión a la base de datos
mysqli_close($link);
?>