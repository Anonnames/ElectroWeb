<?php
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Elimina las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige al index
header("Location: ../index.php");
exit();
