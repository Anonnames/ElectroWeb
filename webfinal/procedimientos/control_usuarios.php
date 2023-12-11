<?php

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

// Obtiene el nombre de usuario desde el formulario con POST
$nombreUsuario = $_POST['nombreUsuario'];

$sql_estado_actual = "SELECT Banned FROM usuario WHERE NombreUsuario = '$nombreUsuario'";
$result_estado_actual = $conn->query($sql_estado_actual);

// Si encuentra algo en la consulta
if ($result_estado_actual->num_rows > 0) {
    $row_estado_actual = $result_estado_actual->fetch_assoc();

    // Consulta para cambiar la propiedad banned. Si esta baneado cambia a desbanear y viceversa.
    $sql_actualizar = "UPDATE usuario SET Banned = CASE WHEN Banned = 0 THEN 1 ELSE 0 END WHERE NombreUsuario = '$nombreUsuario'";

    if ($conn->query($sql_actualizar) === TRUE) {
        // Determina la acción realizada
        $accion = ($row_estado_actual['Banned'] == 0) ? 'baneado' : 'desbaneado';

        echo "'<script>
    function mostrarAlerta() {
        alert(\"El usuario $nombreUsuario ha sido $accion.\");
        window.location.href = \"administracion_usuarios.php\";
    }
    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
    </script>';
    exit();";
    } else {
        echo "Error al actualizar el estado: " . $conn->error;
    }
} else {
    echo "Error al obtener el estado actual del usuario: " . $conn->error;
}

$conn->close();
?>
