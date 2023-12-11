<?php
session_start();
$es_admin = isset($_SESSION['es_admin']) ? $_SESSION['es_admin'] : false;

if (isset($_SESSION['nombreusu'])) {
    $nombreUsuario = $_SESSION['nombreusu'];
}

$servername = "localhost";
$username_bd = "root";
$password_bd = "";
$dbname = "tienda";



// Obtengo el ID del producto desde la URL
if (isset($_GET['id']))
    $idProducto = $_GET['id'];

$conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Obtener el ID del producto desde la URL
if (isset($_GET['id'])) {
    $idProducto = $_GET['id'];

    $sql = "SELECT * FROM producto WHERE IdProducto = $idProducto";
    $result = $conn->query($sql);

    // Verificar la consulta saca algo
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $nombre = $row["Nombre"];
        $descripcion = $row["Descripcion"];
        $cantidad = $row["Cantidad"];
        $precio = $row["Precio"];
        $marca = $row["Marca"];
        $idcat = $row["IdCategoria"];

        // Consulta que obtiene nombres de imagen asociadas a cada producto
        $sql2 = "SELECT NombreFichero FROM foto WHERE IdProducto = $idProducto";
        $result2 = $conn->query($sql2);

        // Verificar la consulta saca algo
        if ($result2->num_rows > 0) {
            // Almacenamos los nombres de fotos en array
            $imagenes = array();

            while ($row2 = $result2->fetch_assoc()) {
                $imagenes[] = $row2["NombreFichero"];
            }

            // Estilos porque no es un archivo HTML, lo hago todo con echo 
            echo "
            <html>
                <head>
                    <title>$nombre</title>
                    <style>
                        /* Estilos CSS aquí... */

                       .carousel-container {
                        position: relative;
                        max-width: 90%;
                        margin: 20px auto;
                        overflow: hidden;
                        }

                        .carousel {
                            display: flex;
                            transition: transform 0.5s ease-in-out;
                            height: 500px;
                            /* Elimina el ancho fijo y permite que el ancho sea automático */
                            width: auto;
                        }

                        .carousel img {
                            max-width: 100%;
                            max-height: 100%;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            object-position: center; /* Centra la imagen dentro del cuadro */
                            border-radius: 8px;
                        }

                        
                        .container {
                            max-width: 800px;
                            margin: 20px auto;
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            text-align: center;
                        }

                        h2 {
                            color: #333;
                        }

                        p {
                            color: #555;
                            font-size: 16px;
                            line-height: 1.5;
                        }
                    
                       

                        button {
                            background-color: #4caf50;
                            color: #fff;
                            padding: 10px 20px;
                            font-size: 16px;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            display: block;
                            margin: auto; /* Centrar horizontalmente */
                            margin-top: 10px; /* Espacio superior */
                            margin-bottom: 20px; /* Espacio inferior */

                        }
                    
                        button:hover {
                            background-color: #45a049;
                        }
                    </style>
                </head>
                <body>
                <header>
    <img src=\"../logos/logo.jpg\" alt=\"Logo de ElectroWeb\">
    <h1>ElectroWeb. Tu Sitio de Tecnología</h1>
    <p>Bienvenido a la última frontera de la innovación.</p>
    <link rel=\"stylesheet\" href=\"../estilos/stylecabecera.css\">


  </header>

  <nav>
    <a href=\"../index.php\">Inicio</a>
</nav>
                    <div class=\"container\">
                        <h2>$nombre</h2>
                        <div class=\"carousel-container\">
                            <div class=\"carousel\">";

            // Agregar imágenes al carrusel
            foreach ($imagenes as $imagen) {
                echo "<img src=\"../fotos_anuncios/$imagen\" alt=\"Foto del producto\">";
            }

            echo "</div>
                        </div>
                        <p><strong>Descripción:</strong> $descripcion</p>
                        <p><strong>Cantidad:</strong> $cantidad</p>
                        <p><strong>Precio:</strong>$precio €</p>
                        <p><strong>Marca:</strong> $marca</p>
                    </div>



                    <form action=\"procesar_carrito.php\" method=\"post\">
                    <div class=\"buttons\">
                    <input type=\"hidden\" name=\"id_producto\" value=\"$idProducto\">
                        <button class\"botoncarrito\"type=\"submit\" name=\"agregar_al_carrito\">Añadir al carrito</button>
                    </div>

                   

                    <script>
                        let currentIndex = 0;
                        const carousel = document.querySelector('.carousel');
                        const totalImages = carousel.childElementCount;

                        function showImage(index) {
                            const translateValue = -index * 100 + '%';
                            carousel.style.transform = 'translateX(' + translateValue + ')';
                        }

                        // Cambiar la imagen cada 3 segundos
                        setInterval(() => {
                            currentIndex = (currentIndex + 1) % totalImages;
                            showImage(currentIndex);
                        }, 3000);
                    </script>
                    <footer>
                    <p>&copy; 2023 Tu Sitio de Tecnología. Todos los derechos reservados.</p>
                  </footer>
                </body>
            </html>
            ";
        } else {
            echo "No se encontraron imágenes para el producto.";
        }
    } else {
        echo "No se encontró el producto.";
    }
} else {
    echo "ID de producto no proporcionado en la URL.";
}

// Cerrar conexión
$conn->close();
?>