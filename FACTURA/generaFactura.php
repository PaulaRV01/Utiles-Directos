<?php
session_start();
if(empty($_SESSION['active'])) {
    header('location: ../');
}
include "../DATOS/conexion/conexion.php";
if(empty($_REQUEST['cl']) || empty($_REQUEST['f'])) {
    echo "No es posible generar la factura.";
} else {
    $codCliente = $_REQUEST['cl'];
    $noFactura = $_REQUEST['f'];
    $consulta = mysqli_query($conexion, "SELECT * FROM configuracion");
    $resultado = mysqli_fetch_assoc($consulta);
    $ventas = mysqli_query($conexion, "SELECT * FROM factura WHERE nofactura = $noFactura");
    $result_venta = mysqli_fetch_assoc($ventas);
    $clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $codCliente");
    $result_cliente = mysqli_fetch_assoc($clientes);
    $productos = mysqli_query($conexion, "SELECT d.nofactura, d.codproducto, d.cantidad, p.codproducto, p.descripcion, p.precio FROM detallefactura d INNER JOIN producto p ON d.nofactura = $noFactura WHERE d.codproducto = p.codproducto");

    
	function generarPDF($resultado, $noFactura, $result_venta, $result_cliente, $productos) {
		require('fpdf/fpdf.php');
	
		$pdf = new FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial', '', 12);
	
		$pdf->Ln(20);
		
		$pdf->Cell(0, 15, utf8_decode("Nombre de la empresa: " . $resultado['nombre']), 0, 1, 'C');
		$pdf->Cell(0, 10, "RUC: " . $resultado['dni'], 0, 1, 'C');
		$pdf->Cell(0, 10,  utf8_decode("Teléfono: ") . $resultado['telefono'], 0, 1, 'C');
		$pdf->Cell(0, 10, utf8_decode("Dirección: " . $resultado['direccion']), 0, 1, 'C');
	
		$pdf->Cell(0, 10,  utf8_decode("Número de Factura: ") . $noFactura, 0, 1, 'C');
		$pdf->Cell(0, 10, "Fecha: " . $result_venta['fecha'], 0, 1, 'C');
		$pdf->Ln(10);
		
		$pdf->Cell(0, 10, "Datos del cliente:", 0, 1, 'C');
		$pdf->Cell(0, 10, "Nombre: " . utf8_decode($result_cliente['nombre']), 0, 1, 'C');
		$pdf->Cell(0, 10,  utf8_decode("Teléfono: " .$result_cliente['telefono']), 0, 1, 'C');
		$pdf->Cell(0, 10,  utf8_decode("Dirección: " .$result_cliente['direccion']), 0, 1, 'C');
		$pdf->Ln(10);

		$pdf->SetX(($pdf->GetPageWidth() - 120) / 2);
		$pdf->Cell(30, 10, "Nombre", 1, 0, 'C');
		$pdf->Cell(30, 10, "Cantidad", 1, 0, 'C');
		$pdf->Cell(30, 10, "Precio", 1, 0, 'C');
		$pdf->Cell(30, 10, "Total", 1, 1, 'C');
	
		$total = 0;
		while ($row = mysqli_fetch_assoc($productos)) {
			$importe = $row['cantidad'] * $row['precio'];
			$total += $importe;
	
			$pdf->SetX(($pdf->GetPageWidth() - 120) / 2);
			$pdf->Cell(30, 10, utf8_decode($row['descripcion']), 1, 0, 'C');
			$pdf->Cell(30, 10, $row['cantidad'], 1, 0, 'C');
			$pdf->Cell(30, 10, number_format($row['precio'], 2, '.', ','), 1, 0, 'C');
			$pdf->Cell(30, 10, number_format($importe, 2, '.', ','), 1, 1, 'C');
		}
	
		$pdf->SetX(($pdf->GetPageWidth() - 120) / 2);
		$pdf->Cell(90, 10, "Total:", 1, 0, 'C');
		$pdf->Cell(30, 10, number_format($total, 2, '.', ','), 1, 1, 'C');
		$pdf->Ln(5);

		$pdf->Cell(0, 5, utf8_decode("Comprobante validado por SUNAT"), 0, 1, 'C');
		$pdf->Cell(0, 10, utf8_decode("Gracias por su preferencia"), 0, 1, 'C');
	
		$pdfFileName = 'compra.pdf';
		$pdf->Output('F', $pdfFileName);
	
		return $pdfFileName;
	}
	
	//NO OLVIDAR: para las capturas eliminar este arreglo
	$responseData = array(
		'success' => true,
		'message' => 'La factura fue emitida a SUNAT de forma exitosa.'
	);


	//API SUNAT
    $url = 'https://back.apisunat.com/personas/v1/sendBill';
    $data = array(
        'personaId' => '65d9f0875915390015d12805',
        'personaToken' => 'DEV_fYRV7CB7Dl1Du0WHcDGu5tvOnTkBf4vOpWI57RFZTrIpCPUahUUdf1D4bNMbSMz2',
        'fileName' => "compra.pdf",
        'documentBody' => $productos,
        'email' => 'reporte@utilesdirectos.com'
    );

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    );

    $curl = curl_init();
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);

	if($response === false) {
		echo 'Error en la solicitud cURL: ' . curl_error($curl);
	} else {
		//$responseData = json_decode($response, true); NO OLVIDAR: para las capturas descomentar esta línea
		if(isset($responseData['success']) && $responseData['success'] === true) {
			echo '<div class="success-message">' . $responseData['message'] . '</div>';
	
			$pdfFileName = generarPDF($resultado, $noFactura, $result_venta, $result_cliente, $productos);
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $pdfFileName . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($pdfFileName));
			readfile($pdfFileName);
		} else {
			echo '<div class="error-message">Hubo un problema al enviar la factura al API: ' . $responseData['error'] . '</div>';
		}
	}
	curl_close($curl);	
}
?>
