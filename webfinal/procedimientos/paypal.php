<?php
$totalCarrito = isset($_POST['total_carrito']) ? $_POST['total_carrito'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src="https://www.paypal.com/sdk/js?client-id=AU-cJer_bnH-mRQ1IUpHZXTSdbpSnDzdvmwJXtzj91QZ47Caw2XBYH9GHW1FUMvO8c5wYKqqiQ0S5W7S&currency=EUR"></script>
</head>
<body>
<?php $totalCarrito = isset($_POST['total_carrito']) ? $_POST['total_carrito'] : 0;?>
    <div id="paypal-button-container"></div>
    <script>
        paypal.Buttons({
            style:{
                color:'blue',
                shape:'pill',
                label:'pay'
            },
            createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $totalCarrito; ?>
                        }
                    }]
                })
            },

            onApprove: function(data,actions){
                actions.order.captura().then(function(detalles){
                    console.log(detalles);
                });
            },

            onCancel:function(data){
                alert("Pago Cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container')
    </script>
</body>
</html>