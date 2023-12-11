<?php
$host = "localhost";
$usuario_bd = "root";
$contrasena_bd = "";
$nombre_bd = "tienda";

$conexion = new mysqli($host, $usuario_bd, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}

// Recuperar datos del formulario
$nombreUsuario = $_POST['username'];
$contrasena = $_POST['password'];
$nombreCompleto = $_POST['fullname'];
$email = $_POST['email'];
$genero = $_POST['gender'];
$direccion = $_POST['address'];
$telefono = $_POST['phone'];
$fechaNacimiento = $_POST['birthdate'];

// Consulta SQL para la inserción
$sql = "INSERT INTO usuario (NombreUsuario, Contraseña, NombreCompleto, Email, Genero, Dirección, Teléfono, FechaNacimiento,Rol,Banned) 
        VALUES ('$nombreUsuario', md5('$contrasena'), '$nombreCompleto', '$email', '$genero', '$direccion', '$telefono', '$fechaNacimiento', 2,0)";

// Ejecutar la consulta
if ($conexion->query($sql) === TRUE) {
    echo "'<script>
    function mostrarAlerta() {
        alert(\"Registro exitoso. Ahora puedes Iniciar Sesión con tu cuenta!!!.\");
        window.location.href = \"login.html\";
    }
    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
    </script>';
    exit();";

    //Ejemplo de correo 
//     $para = "lopezgarciamanuel4@gmail.com";
//     $asunto = "Registro correcto";
//     $mensaje = "Se ha registrado correctamente en nuestra pagina.";
//     $cabeceras = "From: correoadmin@electroweb.es\r\n";
//     $cabeceras .= "MIME-Version: 1.0\r\n";
//     $cabeceras .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//     mail($para, $asunto, $mensaje, $cabeceras);

   header("refresh:4;url=login.html");
} else {
    echo "Error en el registro: " . $conexion->error;
}

// Cerrar la conexión
$conexion->close();
?>