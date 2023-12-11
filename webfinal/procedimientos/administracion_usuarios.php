<?php
session_start();

$es_admin = isset($_SESSION['es_admin']) ? $_SESSION['es_admin'] : false;

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    // Si no es un administrador, redirige a otra página.
    header("Location: usuario_no_autorizado.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <link rel="stylesheet" href="../estilos/style_admin.css">
</head>

<body>
    <?php

    $nombreUsuario = $_SESSION['nombreusu'];
    // Conexión a la base de datos
    $servername = "localhost";
    $username_bd = "root";
    $password_bd = "";
    $dbname = "tienda";

    $conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "SELECT NombreCompleto, NombreUsuario, Banned FROM usuario where NombreUsuario!='$nombreUsuario'";
    $result = $conn->query($sql);
    // Si de la consulta se obtiene algo
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Nombre Completo</th><th>Nombre de Usuario</th><th>Accion a realizar</th></tr>";

        // Mostrar datos de cada usuario
        while ($row = $result->fetch_assoc()) {
            echo "<form action='control_usuarios.php' method='post'>";

            // Campo oculto para mandarlo por POST
            echo "<input type='hidden' name='nombreUsuario' value='" . ($row['NombreUsuario']) . "'>";

            echo "<tr>";
            echo "<td>" . $row['NombreCompleto'] . "</td>";
            echo "<td>" . $row['NombreUsuario'] . "</td>";

            if ($row['Banned'] == 0) {
                echo "<td><button type='submit' name='banear'>Banear</button></td>";
            } else {
                echo "<td><button type='submit' name='desbanear'>Desbanear</button></td>";
            }

            echo "</tr>";
            echo "</form>";
        }

        echo "</table>";
    } else {
        echo "No se encontraron usuarios.";
    }

    $conn->close();
    ?>
</body>

</html>