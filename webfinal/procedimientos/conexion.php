<?php
$servername = "localhost";
$username_bd = "root";
$password_bd = "";
$dbname = "tienda";



$conn = new mysqli($servername, $username_bd, $password_bd, $dbname);
$correcto=false;
// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario de inicio de sesión con lo obtenido por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del POST
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta para verificar si usu existe
    $sql = "SELECT * FROM usuario WHERE NombreUsuario = '$username' AND Contraseña = md5('$password') and Banned=0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Existe //esto no lo hace, redirige directamente al index
        echo "Inicio de sesión exitoso. ¡Bienvenido, $username!";
        $correcto = true;
    } else {
        // no existe o baneado
        echo "'<script>
    function mostrarAlerta() {
        alert(\"Usuario baneado por administrador o contraseña incorrecta.\");
        window.location.href = \"login.html\";
    }
    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
    </script>';
    exit();";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();




$conn = new mysqli($servername, $username_bd, $password_bd, $dbname);
if ($correcto) {
    // Obtener los datos del usuario desde la base de datos
    $sql2 = "SELECT * FROM usuario WHERE NombreUsuario = '$username'";
    $result = $result = $conn->query($sql2);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Iniciar la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Almacenar datos en variables de sesión
        $_SESSION['nombreusu'] = $row['NombreUsuario'];
        $_SESSION['nombrecompleto'] = $row['NombreCompleto'];
        $_SESSION['email'] = $row['Email'];
        $_SESSION['genero'] = $row['Genero'];
        $_SESSION['direccion'] = $row['Dirección'];
        $_SESSION['telefono'] = $row['Teléfono'];
        $_SESSION['fech_nac'] = $row['FechaNacimiento'];

        if ($row['Rol'] == 1) {
            $_SESSION['es_admin'] = true;
        } else {
            $_SESSION['es_admin'] = false;
        }


        // Redirigir después del inicio de sesión
        header("Location: ../index.php");
        exit();
    } else {
        // Esto no debe de llegar aqui porque se para en contraseña incorrecta o baneado solo por prevenir
        echo "Usuario no encontrado";
    }
}
$conn->close();

?>