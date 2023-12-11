<?php
session_start();

$es_admin = isset($_SESSION['es_admin']) ? $_SESSION['es_admin'] : false;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../estilos/stylecabecera.css">
  <link rel="stylesheet" href="../estilos/stylecontacto.css">

  <title>Contacto</title>
</head>

<body>
  <header>
    <img src="../logos/logo.jpg" alt="Logo de ElectroWeb">
    <h1>ElectroWeb. Tu Sitio de Tecnología</h1>
    <p>Bienvenido a la última frontera de la innovación.</p>

  </header>

  <nav>
    <a href="../index.php">Inicio</a>
  </nav>
  <section>
    <form action="procesar_contacto.php" method="post">
      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="email">Correo Electrónico:</label>
      <input type="email" id="email" name="email" required>

      <label for="mensaje">Mensaje:</label>
      <textarea id="mensaje" name="mensaje" rows="4" required></textarea>

      <button type="submit">Enviar Mensaje</button>
    </form>
  </section>
  <footer>
    <p>&copy; 2023 Tu Sitio de Tecnología. Todos los derechos reservados.</p>
  </footer>
</body>

</html>