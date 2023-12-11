<?php
session_start();

$nombreUsuario = isset($_SESSION['nombreusu']) ? $_SESSION['nombreusu'] : '';
?>
<html>

<head>
    <link rel="stylesheet" href="../estilos/styleregistro.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Personales</title>
</head>

<body>
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

    $sql = "SELECT * FROM usuario WHERE NombreUsuario = '$nombreUsuario'";
    $result = $conn->query($sql);

    // Verifica si la consulta encuentra algo
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $contrasena = $row['Contraseña'];
        $nombreCompleto = $row['NombreCompleto'];
        $email = $row['Email'];
        $genero = $row['Genero'];
        $direccion = $row['Dirección'];
        $telefono = $row['Teléfono'];
        $fechaNacimiento = $row['FechaNacimiento'];
    } else {
        echo "No se encontraron datos para el usuario actual.";
    }

    $conn->close();
    ?>


    <div id="formulario">
    <header>
        <img src="../logos/logo.jpg" alt="Logo de ElectroWeb">
        <h1 class="titulo">ElectroWeb. Tu Sitio de Tecnología</h1>
        <p>Bienvenido a la última frontera de la innovación.</p>
        <link rel="stylesheet" href="../estilos/stylecabecera.css">
    </header>

    <nav>
        <a href="../index.php">Inicio</a>
    </nav>
    <div id="form-container">

        <h2>En este espacio, puedes modificar todos los datos de tu cuenta.</h2>
        <p>En caso de que quiera conservar su contraseña anterior pero desee cambiar otro dato de su cuenta, introduzcala .Si lo desea, tambien puede cambiar su contraseña.</p>
        <form action="../procedimientos/actualizar_datos.php" method="post">
        <input type="hidden" name="nombreUsuario" value="<?php echo $nombreUsuario; ?>">
        <input type="hidden" name="contrasena_actual" value="<?php echo $contrasena; ?>">

        <label for="nombreCompleto">Nombre Completo:</label>
        <input type="text" id="nombreCompleto" name="nombreCompleto" value="<?php echo $nombreCompleto; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

        <label for="genero">Género:</label>
        <select id="genero" name="genero">
            <option value="masculino" <?php echo ($genero == 'masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="femenino" <?php echo ($genero == 'femenino') ? 'selected' : ''; ?>>Femenino</option>
        </select>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>">

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" value="<?php echo $telefono; ?>">

        <label for="fechaNacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo $fechaNacimiento; ?>">

        <label for="contrasena">Nueva Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>
