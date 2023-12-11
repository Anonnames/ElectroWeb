<link rel="stylesheet" href="estilos/stylecabecera.css">

<?php
$servername = "localhost";
$username_bd = "root";
$password_bd = "";
$dbname = "tienda";

// Crear conexión
$conn = new mysqli($servername, $username_bd, $password_bd, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtiene los productos que tengas al menos algo de stock
$sql = "SELECT * FROM producto where Cantidad > 0";
$result = $conn->query($sql);

// Verificar si la consulta sca algo
if ($result->num_rows > 0) {
    // Mostrar los productos 
    $contador = 0;
    while ($row = $result->fetch_assoc()) {
        $nombre = $row["Nombre"];
        $descripcion = $row["Descripcion"];
        $precio=$row["Precio"];
        $idProducto = $row["IdProducto"];

        // Consulta que obtiene nombres de imagen asociadas a cada producto
        $sql2 = "SELECT NombreFichero FROM foto WHERE IdProducto = $idProducto";
        $result2 = $conn->query($sql2);

         // Verificar la consulta saca algo
        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $nombreFichero = $row2["NombreFichero"];

            // Mostrar la información del producto con la imagen y boton de ver
            echo "<div class=\"col\">
                <div class=\"producto-card\">
                    <a href=\"procedimientos/detalle_producto.php?id=$idProducto\" style=\"text-decoration: none; color: black;\"> <!-- Estilos adicionales para el enlace -->
                        <div class=\"foto-producto\">
                            <img src=\"../fotos_anuncios/$nombreFichero\" class=\"bd-placeholder-img card-img-top object-fit-scale\"  alt=\"Foto del producto\" style=\"width: 100%; height: 225px; object-fit: cover;\"> 
                        </div>
                        <div class=\"info-producto\">
                            <h5 class=\"card-title\">$nombre</h5>
                            <h5 class=\"card-precio\" style=\"text-align: right;\">$precio €</h5>
                        </div>
                        <button style=\"background-color: #007bff; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;\" onmouseover=\"this.style.backgroundColor='#0056b3'\" onmouseout=\"this.style.backgroundColor='#007bff'\">Ver</button>
                        </a>
                </div>
            </div>";
        }
    }
}else{
    echo"No se han encontrado productos.";
}

// Cerrar conexión
$conn->close();
?>
