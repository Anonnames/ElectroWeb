<?php
session_start();

$host = "localhost";
$usuario_bd = "root";
$contrasena_bd = "";
$nombre_bd = "tienda";


// Recuperar datos del formulario
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$marca = $_POST['marca'];
$usuario = $_SESSION['nombreusu'];
$categoria=$_POST['idCategoria'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    insertar_producto($host, $usuario_bd, $contrasena_bd, $nombre_bd, $nombre, $descripcion, $cantidad, $precio, $marca, $usuario, $categoria);
}



function insertar_producto($host, $usuario_bd, $contrasena_bd, $nombre_bd, $nombre, $descripcion, $cantidad, $precio, $marca, $usuario, $categoria) 
{
    $conexion = new mysqli($host, $usuario_bd, $contrasena_bd, $nombre_bd);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión a la base de datos: " . $conexion->connect_error);
    }

    // Insertar producto
    $sql = "INSERT INTO producto (Nombre, Descripcion, Cantidad, Precio, Marca, NombreUsuario, IdCategoria) 
        VALUES ('$nombre', '$descripcion', '$cantidad', '$precio', '$marca', '$usuario', '$categoria')";

    // Ejecutar la consulta y mostrar que se ha añadido
    if ($conexion->query($sql) === TRUE) {
        echo "'<script>
                    function mostrarAlerta() {
                        alert(\"Producto añadido correctamente.\");
                        window.location.href = \"../index.php\";
                    }
                    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
                    </script>';
                    exit();";
            

        // ID del último producto insertado
        $ultimoId = $conexion->insert_id;

        // Guardar las imágenes en el directorio local
        $directorioImagenes = "../fotos_anuncios/";

        for ($i = 1; $i <= 3; $i++) {
            $nombreArchivo = $_FILES["fileInput{$i}"]["name"];
            $nombrefoto = "$ultimoId" . "_" . "$i" . ".png"; //Cambiar nombre de foto en directorio local
            $rutaDestino = $directorioImagenes . $nombrefoto; 

            // Imagen subida bien
            if (move_uploaded_file($_FILES["fileInput{$i}"]["tmp_name"], $rutaDestino)) {
                //echo "La imagen {$i} se ha subido correctamente.<br>";

                // Insertar datos en la tabla foto mirando el nombre del directorio
                $sqlFoto = "INSERT INTO foto (IdProducto, NumFotos, NombreFichero) 
                VALUES ('$ultimoId', '$i', '$nombrefoto')";
                $conexion->query($sqlFoto);
            }
        }

        header("refresh:4;url=../index.php");
        return 1;

    } else {
        echo "Error en el registro: " . $conexion->error;
    }

    // Cerrar la conexión
    $conexion->close();
}

?>