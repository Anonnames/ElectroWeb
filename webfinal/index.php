<?php
session_start();


$es_admin = isset($_SESSION['es_admin']) ? $_SESSION['es_admin'] : false;

?>

<!DOCTYPE html>
<html lang="es">

<HEAD>
    <TITLE>ElectroWeb</TITLE>
    <link rel="stylesheet" href="estilos/style.css">
    <link rel="icon" href="logos/logo.jpg" type="image/x-icon">
    <META NAME="keywords" CONTENT="ElectroWeb">
    <META NAME="description" CONTENT="ElectroWeb">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</HEAD>

<body>

    <header>
        <img src="logos/logo.jpg" alt="Logo de ElectroWeb">
        <h1>ElectroWeb. Tu Sitio de Tecnología</h1>
        <p>Bienvenido a la última frontera de la innovación.</p>
    </header>

    <nav>
        <div class="menunormal">
            <a href="index.php">Inicio</a>
            <a href="../procedimientos/contacto.php">Contacto</a>

            <?php if (!isset($_SESSION['nombreusu'])): ?>
                <a href="../procedimientos/login.html">Iniciar Sesión</a>
            <?php endif; ?>
            <?php if ($es_admin): ?>
                <a href="../procedimientos/nuevoproducto.php">Nuevo Producto</a>
                <a href="../procedimientos/administracion_usuarios.php">Administrar Usuarios</a>
            <?php endif; ?>
            <a href="../procedimientos/mostrar_carrito.php">
                <img src="logos/carrito.png"
                    style="width: 30px; height: auto; margin-right: 5px; vertical-align: middle;">
            </a>


        </div>

        <?php if (isset($_SESSION['nombreusu'])): ?>
            <div class="usuario-menu" style="line-height: 1;">
                <!-- Agrega el logo aquí -->
                <img src="logos/login.png" style="width: 40px; height: auto; margin-right: 10px; vertical-align: middle;">
                <p style="margin: 0; display: inline-block; vertical-align: middle;">
                    <?php echo $_SESSION['nombreusu']; ?>
                </p>
                <div class="submenu">
                    <a href="../procedimientos/datos_personales.php" class="datos">Datos Personales</a>
                    <br>
                    <a class="datos" href="procedimientos/cerrar_sesion.php">Cerrar Sesión</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <section>
        <?php include 'procedimientos/funciones.php'; ?>
    </section>


    <footer>
        <p>&copy; 2023 Tu Sitio de Tecnología. Todos los derechos reservados.</p>
        <div class="redes-sociales">
            <div class="icono-red-social">
                <a href="https://www.facebook.com" target="_blank">
                    <img src="logos/facebook.png" alt="Facebook">
                </a>
            </div>

            <div class="icono-red-social">
                <a href="https://twitter.com" target="_blank">
                    <img src="logos/twitter.png" alt="Twitter">
                </a>
            </div>

            <div class="icono-red-social">
                <a href="https://www.instagram.com" target="_blank">
                    <img src="logos/instagram.png" alt="Instagram">
                </a>
            </div>

            <div class="icono-red-social">
                <a href="https://www.tiktok.com" target="_blank">
                    <img src="logos/tiktok.png" alt="TikTok">
                </a>
            </div>
        </div>
    </footer>

</body>

</html>