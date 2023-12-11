<?php
// Incluir biblioteca TCPDF
require_once('../pdf/tcpdf.php');

// Obtiene los datos del POST del carrito
$nombreUsuario = isset($_POST['nombre_usuario']) ? $_POST['nombre_usuario'] : '';
$totalCarrito = isset($_POST['total_carrito']) ? $_POST['total_carrito'] : 0;

// Verifica que se hayan recibido los datos
if (empty($nombreUsuario) || $totalCarrito <= 0) {
    die('Error: Datos insuficientes para generar la factura.');
}

// Crea una instancia de la clase TCPDF
$pdf = new TCPDF();

//título del documento
$pdf->SetTitle('Factura ElectroWeb');

// Añade página al documento
$pdf->AddPage();

// Agrega contenido al PDF
$content = "
    <h1>Factura de Compra</h1>
    <p>Usuario: $nombreUsuario</p>
    <p>Total del Carrito: $totalCarrito €</p>
    <p>Fecha: " . date('Y-m-d H:i:s') . "</p>
    <p>¡Gracias por tu compra en ElectroWeb!</p>
";

$pdf->writeHTML($content, true, false, true, false, '');

// Genera el PDF y lo abre
$pdf->Output('factura.pdf', 'I');

?>


