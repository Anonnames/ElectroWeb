<?php

session_start();

if (isset($_POST['agregar_al_carrito'])) {
    // Obtener el ID del producto y el nombre de usuario conectado
    $idProducto = $_POST['id_producto'];
    $nombreUsuario = isset($_SESSION['nombreusu']) ? $_SESSION['nombreusu'] : '';

    $servername = "localhost";
    $username_bd = "root";
    $password_bd = "";
    $dbname = "tienda";

    $conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
  
    /**
     *  Obtener carrito con Cod_Compra 0 del usuario conectado
     *
     * @param  mixed $nombreUsuario
     * @param  mixed $conn
     * @return void
     */
    function obtenerIdCarritoAbierto($nombreUsuario, $conn)
    {
        //Si hay carrito abierto devuelve el IdCarrito, si no devuelve false.
        $sql = "SELECT IdCarrito FROM carrito WHERE NombreUsuario = '$nombreUsuario' AND Cod_Compra = 0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['IdCarrito'];
        } else {
            return false;
        }
    }

    /**
     *  Cuanta cantidad de productos hay en un carrito especifico     
     *
     * @param  mixed $idCarrito
     * @param  mixed $idProducto
     * @param  mixed $conn
     * @return void
     */
    function obtenerCantidadProductoEnCarrito($idCarrito, $idProducto, $conn)
    {
        $sql = "SELECT COUNT(*) as cantidad FROM producto_carrito WHERE IdCarrito = $idCarrito AND IdProducto = $idProducto";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['cantidad'];
        } else {
            return 0;
        }
    }

    /**
     *   Si no hay carrito de usuario con cod_compra 0, crear uno    
     *
     * @param  mixed $nombreUsuario
     * @param  mixed $conn
     * @return void
     */
    function crearNuevoCarrito($nombreUsuario, $conn)
    {
        //Crea carrito y devuelve el id 
        $sqlInsertCarrito = "INSERT INTO carrito (NombreUsuario,Cod_Compra) VALUES ('$nombreUsuario',0)";
        if ($conn->query($sqlInsertCarrito) === TRUE) {
            return $conn->insert_id;
        } else {
            die("Error al crear el carrito: " . $conn->error);
        }
    }

    /**
     *   Consulta si hay stock   
     *
     * @param  mixed $idProducto
     * @param  mixed $conn
     * @return void
     */
    function obtenerCantidadDisponibleProducto($idProducto, $conn)
    {
        $sql = "SELECT Cantidad FROM producto WHERE IdProducto = $idProducto";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Cantidad'];
        } else {
            return null;
        }
    }

    /**
     * Insertar producto en carrito    
     *
     * @param  mixed $idCarrito
     * @param  mixed $idProducto
     * @param  mixed $conn
     * @return void
     */
    function insertarProductoEnCarritoExistente($idCarrito, $idProducto, $conn)
    {
        // miramos si hay stock
        $cantidadDisponibleProducto = obtenerCantidadDisponibleProducto($idProducto, $conn);

        if ($cantidadDisponibleProducto === null) {
            echo "No se puede determinar la cantidad disponible para este producto.";
            header("refresh:4;url=../index.php");
            return;
        }

        // Cuanta cantidad de productos en un carrito especifico 
        $cantidadEnCarrito = obtenerCantidadProductoEnCarrito($idCarrito, $idProducto, $conn);

        // Verifica si la cantidad total supera el stock
        if ($cantidadEnCarrito >= $cantidadDisponibleProducto) {
            echo "No se puede agregar más unidades de este producto al carrito. Cantidad disponible: $cantidadDisponibleProducto unidades.";
            header("refresh:4;url=../index.php");
        } else {
            //$cantidadEnCarritoActualizada = $cantidadEnCarrito + 1;

            //Inserta el producto en el carrito
            $sqlInsertRelacion = "INSERT INTO producto_carrito (IdProducto, IdCarrito) VALUES ('$idProducto', '$idCarrito')";
            if ($conn->query($sqlInsertRelacion) === TRUE) {
                echo "'<script>
                function mostrarAlerta() {
                    alert(\"Producto añadido al carrito correctamente.\");
                    window.location.href = \"../index.php\";
                }
                setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
              </script>';
              exit();";
            } else {
                die("Error al agregar el producto al carrito: " . $conn->error);
            }
        }
    }

  

    //Ahora usamos todo lo anterior paso por paso, haciendo todas las comprobaciones.
    // 1: Arreglar el tema de carrito abierto o no
    $idCarritoExistente = obtenerIdCarritoAbierto($nombreUsuario, $conn);

    // 2: Insertar en la tabla Producto_Carrito
    if ($idCarritoExistente !== false) {
        // Utilizamos el carrito existente
        insertarProductoEnCarritoExistente($idCarritoExistente, $idProducto, $conn);
    } else {
        // Creamos un nuevo carrito
        $nuevoIdCarrito = crearNuevoCarrito($nombreUsuario, $conn);

        // Realizamos la inserción en Producto_Carrito
        insertarProductoEnCarritoExistente($nuevoIdCarrito, $idProducto, $conn);
    }


    // Cierra la conexión
    $conn->close();
}


if (isset($_POST['eliminar_producto'])) {
    $idProducto = $_POST['id_producto'];
    $nombreUsuario = isset($_SESSION['nombreusu']) ? $_SESSION['nombreusu'] : '';

    $servername = "localhost";
    $username_bd = "root";
    $password_bd = "";
    $dbname = "tienda";

    $conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // coger el ID del carrito del usuario actual abierto
    $sqlCarrito = "SELECT * FROM carrito WHERE NombreUsuario = '$nombreUsuario' AND Cod_Compra = 0";
    $resultCarrito = $conn->query($sqlCarrito);

    if ($resultCarrito->num_rows > 0) {
        $rowCarrito = $resultCarrito->fetch_assoc();
        $idCarrito = $rowCarrito['IdCarrito'];

        // quitar el producto del carrito
        $sqlEliminarProducto = "DELETE FROM producto_carrito WHERE IdCarrito = $idCarrito AND IdProducto = $idProducto";

        if ($conn->query($sqlEliminarProducto) === TRUE) {
            echo "'<script>
                function mostrarAlerta() {
                    alert(\"Producto eliminado del carrito correctamente.\");
                    window.location.href = \"mostrar_carrito.php\";
                }
                setTimeout(mostrarAlerta, 100); // Espera 100 milisegundos antes de ejecutar mostrarAlerta
            </script>';
            exit();";
        } else {
            echo "Error al eliminar el producto del carrito: " . $conn->error;
        }
    } else {
        echo "No se encontró el carrito para el usuario actual.";
    }

    // Cerrar la conexión
    $conn->close();
}


?>