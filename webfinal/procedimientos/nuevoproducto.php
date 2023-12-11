<?php
session_start();

$es_admin = isset($_SESSION['es_admin']) ? $_SESSION['es_admin'] : false;

if (!isset($_SESSION['es_admin']) || $_SESSION['es_admin'] !== true) {
    // Si no es un administrador, redirige a otra pagina
    header("Location: usuario_no_autorizado.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto</title>

    <!-- Agregar el icono (favicon) -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" tyoe="text/css" href="../estilos/stylenuevo.css">



</head>

<body>
    <header>
        <img src="../logos/logo.jpg" alt="Logo de ElectroWeb">
        <h1>ElectroWeb. Tu Sitio de Tecnología</h1>
        <p>Bienvenido a la última frontera de la innovación.</p>
        <link rel="stylesheet" href="../estilos/stylecabecera.css">

        <script>
            function validarNumeroPositivo(input) {
                var valor = input.value;

                // Reemplaza las comas por puntos si lo necesita
                valor = valor.replace(',', '.');

                // Convierte el valor a float
                var numero = parseFloat(valor);

                // Mira si el num es valido y positivo
                if (!isNaN(numero) && numero >= 0) {
                    // num valido
                } else {
                    // num no valido
                    input.value = '';
                    alert('Por favor, ingrese un número positivo válido en el campo de precio.');
                }
            }
        </script>

    </header>

    <nav>
        <a href="../index.php">Inicio</a>
    </nav>

    <form action="procesarnuevoproducto.php" method="post" enctype="multipart/form-data">

        <h1>Nuevo Producto</h1>
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="descripcion">Descripción del Producto:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="cantidad">Cantidad Disponible:</label>
        <input type="number" id="cantidad" name="cantidad" required>

        <label for="precio">Precio del Producto:</label>
        <input type="number" id="precio" name="precio" step="1" required oninput="validarNumeroPositivo(this)">

        <label for="marca">Marca del Producto:</label>
        <input type="text" id="marca" name="marca" required>

        <label for="idCategoria">Categoría del Producto:</label>
        <select id="idCategoria" name="idCategoria" required>
            <?php
            // Conexión a la base de datos 
            $servername = "localhost";
            $username_bd = "root";
            $password_bd = "";
            $dbname = "tienda";

            $conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtiene categoria de tabla
            $sql = "SELECT IdCategoria, DescripcionPadre FROM categoria";
            $result = $conn->query($sql);

            // Mostrar resultado de consulta en el select 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["IdCategoria"] . '">' . $row["DescripcionPadre"] . '</option>';
                }
            }

            // Cerrar la conexión
            $conn->close();
            ?>

        </select>
        <div class="row">
            <div class="item col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="box" id="box1">
                    <!-- Primer cuadro -->
                </div>
                <input type="file" class="file-button" id="fileInput1" name="fileInput1"
                    accept=".png, .gif, .jpg, .jpeg" onchange="previewImage('fileInput1', 'box1')">
            </div>
            <div class="item col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="box" id="box2">
                    <!-- Segundo cuadro -->
                </div>
                <input type="file" class="file-button" id="fileInput2" name="fileInput2"
                    accept=".png, .gif, .jpg, .jpeg" onchange="previewImage('fileInput2', 'box2')">
            </div>
            <div class="item col-lg-4 col-md-12 col-sm-12 col-xs-12">
                <div class="box" id="box3">
                    <!-- Tercer cuadro -->
                </div>
                <input type="file" class="file-button" id="fileInput3" name="fileInput3"
                    accept=".png, .gif, .jpg, .jpeg" onchange="previewImage('fileInput3', 'box3')">
            </div>
        </div>

        <button type="submit">Crear Producto</button>
    </form>
    <footer>
        <p>&copy; 2023 Tu Sitio de Tecnología. Todos los derechos reservados.</p>
    </footer>

    <script>
        function previewImage(inputId, boxId) {
            console.log("ejecutandose");
            var input = document.getElementById(inputId);
            var box = document.getElementById(boxId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    box.style.backgroundImage = "url('" + e.target.result + "')";
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>