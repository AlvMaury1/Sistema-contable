<?php
session_start();
require('libs/fpdf.php');

$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

$gestion = $_POST['gestion'] ?? '';
$periodo = $_POST['periodo'] ?? '';
$mes = $_POST['mes_numero'] ?? '';
$ventas_total = $_POST['sumaVentas'] ?? 0;
$compras_total = $_POST['sumaCompras'] ?? 0;
$cantidad_ventas = $_POST['cantidadV'] ?? 0;
$cantidad_compras = $_POST['cantidadC'] ?? 0;

$id_cliente = $_POST['idcliente'] ?? ($_SESSION['id_usuario'] ?? null);

// Obtener datos del cliente desde la tabla usuarios
$nit = '';
$razon_social = '';
if ($id_cliente !== null) {
    $sql_cliente = "SELECT nit, razon_social FROM usuarios WHERE id = ?";
    $stmt_cliente = $conexion->prepare($sql_cliente);
    $stmt_cliente->bind_param("i", $id_cliente);
    $stmt_cliente->execute();
    $res_cliente = $stmt_cliente->get_result();
    if ($res_cliente && $res_cliente->num_rows > 0) {
        $row_cliente = $res_cliente->fetch_assoc();
        $nit = $row_cliente['nit'];
        $razon_social = $row_cliente['razon_social'];
    }
}

$iva_cf = round($compras_total * 0.13, 2);

// Verificar si ya existe una consolidación
$sql_check = "SELECT * FROM consolidaciones WHERE gestion = ? AND periodo = ? AND id_cliente = ? LIMIT 1";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("isi", $gestion, $periodo, $id_cliente);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check && $result_check->num_rows > 0) {
    $row = $result_check->fetch_assoc();
    $consolidacion_id = $row['id'];

    $pdf_filename = "Consolidacion_{$consolidacion_id}_{$periodo}_{$gestion}_cliente{$id_cliente}.pdf";
    generate_pdf($pdf_filename, $ventas_total, $compras_total, $cantidad_ventas, $cantidad_compras, $iva_cf, $nit, $razon_social, $gestion, $periodo);

    header("Location: pdfs/$pdf_filename");
    exit;
}

// Insertar nueva consolidación
$sql_insert = "INSERT INTO consolidaciones (gestion, periodo, mes, total_ventas, total_compras, cantidad_ventas, cantidad_compras, id_cliente) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conexion->prepare($sql_insert);
if ($stmt_insert) {
    $stmt_insert->bind_param("isiddiii", $gestion, $periodo, $mes, $ventas_total, $compras_total, $cantidad_ventas, $cantidad_compras, $id_cliente);
    $stmt_insert->execute();
    $consolidacion_id = $stmt_insert->insert_id;

    $pdf_filename = "Consolidacion_{$consolidacion_id}_{$periodo}_{$gestion}_cliente{$id_cliente}.pdf";
    generate_pdf($pdf_filename, $ventas_total, $compras_total, $cantidad_ventas, $cantidad_compras, $iva_cf, $nit, $razon_social, $gestion, $periodo);

    header("Location: pdfs/$pdf_filename");
    exit;
} else {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

function generate_pdf($pdf_filename, $ventas_total, $compras_total, $cantidad_ventas, $cantidad_compras, $iva_cf, $nit, $razon_social, $gestion, $periodo) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'CONSTANCIA DE REGISTRO DE COMPRAS Y VENTAS',0,1,'C');
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,6,'N: 1',0,1,'C');
    $pdf->Ln(5);

    // Datos contribuyente
    $pdf->Cell(40,6,'NIT:',1);
    $pdf->Cell(60,6,$nit,1);
    $pdf->Cell(40,6,'Razon Social:',1);
    $pdf->Cell(50,6,$razon_social,1);
    $pdf->Ln();
    $pdf->Cell(40,6,'Periodo:',1);
    $pdf->Cell(60,6,$periodo,1);
    $pdf->Cell(40,6,'Gestion:',1);
    $pdf->Cell(50,6,$gestion,1);
    $pdf->Ln();
    $pdf->Cell(40,6,'Fecha y Hora:',1);
    $pdf->Cell(60,6,date("d/m/Y h:i:s A"),1);
    $pdf->Cell(40,6,'Estado:',1);
    $pdf->Cell(50,6,'ORIGINAL',1);
    $pdf->Ln(10);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,8,'INFORMACION DE CONSOLIDACION',1,1,'C'); // Título unificado de tabla
$pdf->Cell(90,8,'VENTAS',1,0,'C');
$pdf->Cell(90,8,'COMPRAS',1,1,'C');

    $pdf->SetFont('Arial','',9);
    $pdf->Cell(45,6,'Cantidad Facturas:',1);
    $pdf->Cell(45,6,$cantidad_ventas,1);
    $pdf->Cell(45,6,'Cantidad Facturas:',1);
    $pdf->Cell(45,6,$cantidad_compras,1);
    $pdf->Ln();
    $pdf->Cell(45,6,'Importe Total:',1);
    $pdf->Cell(45,6,number_format($ventas_total,2),1);
    $pdf->Cell(45,6,'Importe Total:',1);
    $pdf->Cell(45,6,number_format($compras_total,2),1);
    $pdf->Ln();
    $pdf->Cell(45,6,'Importe IVA CF:',1);
    $pdf->Cell(45,6,'0.00',1);
    $pdf->Cell(45,6,'Importe IVA CF:',1);
    $pdf->Cell(45,6,number_format($iva_cf,2),1);
    $pdf->Ln();
    $pdf->Cell(45,6,'Importe con Derecho:',1);
    $pdf->Cell(45,6,'0.00',1);
    $pdf->Cell(45,6,'Importe con Derecho CF:',1);
    $pdf->Cell(45,6,number_format($compras_total,2),1);
    $pdf->Ln();
    $pdf->Cell(45,6,'Importe sin Derecho:',1);
    $pdf->Cell(45,6,'0.00',1);
    $pdf->Cell(45,6,'Importe No Sujetas IVA:',1);
    $pdf->Cell(45,6,'0.00',1);
    $pdf->Ln(12);

    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(0,6,'Impresion con validez probatoria conforme al Articulo 6 del D.S. 27310',0,1,'C');

    $pdf->Output('F', 'pdfs/'.$pdf_filename);
}
?>
