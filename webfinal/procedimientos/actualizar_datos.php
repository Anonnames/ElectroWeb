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

// Se obtienen datos del form enviados antes por POST
$nombreUsuario = $_POST['nombreUsuario'];
$contrasena_actual = $_POST['contrasena_actual'];
$contrasena = $_POST['contrasena'];
$nombreCompleto = $_POST['nombreCompleto'];
$email = $_POST['email'];
$genero = $_POST['genero'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$fechaNacimiento = $_POST['fechaNacimiento'];

// Verifica si la contraseña ha cambiado
if ($contrasena !== $contrasena_actual) {
  $contrasena=md5($contrasena);
}

$sql = "UPDATE usuario SET Contraseña='$contrasena', NombreCompleto='$nombreCompleto', Email='$email', Genero='$genero', Dirección='$direccion', Teléfono='$telefono', FechaNacimiento='$fechaNacimiento' WHERE NombreUsuario='$nombreUsuario'";

if ($conn->query($sql) === TRUE) {
    echo "'<script>
    function mostrarAlerta() {
        alert(\"Datos actualizados correctamente.\");
        window.location.href = \"../index.php\";
    }
    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
    </script>';
    exit();";

} else {
    echo "Error al actualizar datos: " . $conn->error;
}

$conn->close();
?>