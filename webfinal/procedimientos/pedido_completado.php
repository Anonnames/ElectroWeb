<?php
session_start();
$precio = isset($_GET['precio']) ? $_GET['precio'] : 'Precio no disponible';
$fechaActual = date('Y-m-d')
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Completado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }

        .confirmation-message {
            margin-top: 50px;
        }

        .order-details {
            margin-top: 30px;
        }

        .mensaje {
            font-size: 1.5em;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>
    <div class="confirmation-message">
        <p class="mensaje">¡Gracias por tu pedido!</p>
        <p>Tu pedido ha sido completado con éxito.</p>
    </div>

    <div class="order-details">
        <h2>Detalles del Pedido</h2>
        <p><strong>Fecha del Pedido:</strong> <?php echo $fechaActual; ?></p>
        <p><strong>Total:</strong> <?php echo $precio; ?></p>
    </div>
</body>
</html>
