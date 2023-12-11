<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <!-- Estilos añadidos a los de stylecarrito -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        section {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .producto {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }

        .carrito-vacio {
            color: #555;
            text-align: center;
        }

        form[action="generar_pdf.php"] button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form[action="generar_pdf.php"] button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <header>
        <img src="../logos/logo.jpg" alt="Logo de ElectroWeb">
        <h1>ElectroWeb. Tu Sitio de Tecnología</h1>
        <p>Bienvenido a la última frontera de la innovación.</p>
        <link rel="stylesheet" href="../estilos/stylecabecera.css">
        <link rel="stylesheet" href="../estilos/stylecarrito.css">

    </header>

    <nav>
        <a href="../index.php">Inicio</a>
    </nav>

    <section>
        <?php
        session_start();

        $nombreUsuario = isset($_SESSION['nombreusu']) ? $_SESSION['nombreusu'] : '';

        if (empty($nombreUsuario)) {
            // Si no hay sesion, alert de error y redirigir a logueo
            echo '<script>
                    function mostrarAlerta() {
                        alert("No hay ningún usuario logueado. Inicie Sesión o Registrese.");
                        window.location.href = "login.html";
                    }
                    setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
                  </script>';
            exit();
        }

        $servername = "localhost";
        $username_bd = "root";
        $password_bd = "";
        $dbname = "tienda";

        $conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Obtener el ID del carrito activo del usuario logueado
        $sqlCarrito = "SELECT * FROM carrito WHERE NombreUsuario = '$nombreUsuario' and Cod_Compra=0";
        $resultCarrito = $conn->query($sqlCarrito);

        if ($resultCarrito->num_rows > 0) {
            $rowCarrito = $resultCarrito->fetch_assoc();
            $idCarrito = $rowCarrito['IdCarrito'];


            $sqlProductosCarrito = "
    SELECT 
        producto.IdProducto,
        producto.Nombre, 
        producto.Descripcion, 
        producto.Precio,
        COUNT(producto_carrito.IdProducto) AS CantidadProductosEnCarrito
    FROM 
        producto_carrito
        JOIN producto ON producto_carrito.IdProducto = producto.IdProducto
        JOIN carrito ON producto_carrito.IdCarrito = carrito.IdCarrito
    WHERE 
        carrito.Cod_Compra = 0 AND carrito.IdCarrito = $idCarrito
    GROUP BY
        producto.IdProducto";




            $resultProductosCarrito = $conn->query($sqlProductosCarrito);
            if ($resultProductosCarrito->num_rows > 0) {
                // Mostrar los productos en el carrito
                $totalCarrito = 0;
                while ($rowProducto = $resultProductosCarrito->fetch_assoc()) {
                    $preciototal = $rowProducto['Precio'] * $rowProducto['CantidadProductosEnCarrito'];
                    $totalCarrito += $preciototal;

                    echo '<div class="producto">';
                    echo '<h2>' . $rowProducto['Nombre'] . '</h2>';
                    echo '<p><strong>Descripción:</strong> ' . $rowProducto['Descripcion'] . '</p>';
                    echo '<p><strong>Precio Unitario:</strong> ' . $rowProducto['Precio'] . "€" . '</p>';

                    // Mostrar la cantidad de ese producto
                    echo '<div class="cantidad-productos">' . $rowProducto['CantidadProductosEnCarrito'] . '</div>';

                    // Eliminar producto
                    echo '<form action="procesar_carrito.php" method="post">';
                    echo '<input type="hidden" name="id_producto" value="' . $rowProducto['IdProducto'] . '">';
                    echo '<button type="submit" name="eliminar_producto" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">';
                    echo '<img src="../logos/eliminar.png" alt="Eliminar Producto" style="width: 50px; height: 50px;">';
                    echo '</button>';
                    echo '</form>';
                    echo '</div>';
                }
                echo '<p><strong>Total del carrito:</strong>' . $totalCarrito . '€</p>';
                // Agregar el formulario para enviar los datos por post y generar el archivo generar_pdf.php
                echo '<form action="generar_pdf.php" method="post" target="_blank">';
                echo '<input type="hidden" name="nombre_usuario" value="' . $nombreUsuario . '">';
                echo '<input type="hidden" name="total_carrito" value="' . $totalCarrito . '">';
                echo '<button type="submit">Generar Factura PDF</button>';
                echo '</form>';
                echo '<br>';
                echo '<form action="paypal.php" method="post" target="_blank">';
                echo '<input type="hidden" name="total_carrito" value="' . $totalCarrito . '">';
                echo '<button type="submit">Pagar con PayPal</button>';
                echo '</form>';
               // include('paypal.php');

            } else {
                echo '<p class="carrito-vacio">El carrito está vacío.</p>';
            }
        } else {
            echo '<p class="carrito-vacio">No se encontró el carrito para el usuario actual.</p>';
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </section>
    <footer>
        <p>&copy; 2023 Tu Sitio de Tecnología. Todos los derechos reservados.</p>
    </footer>
</body>

</html>