<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['codRol']) || $_SESSION['codRol'] != 1) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
include 'conexion.inc.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$fecha = $_POST['fecha'] ?? '';
$nro_factura = $_POST['nro_factura'] ?? '';
$nit_ci_proveedor = $_POST['nit_ci_proveedor'] ?? '';
$razon_social = $_POST['razon_social'] ?? '';
$monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
$tipo_de_compra = $_POST['tipo_de_compra'] ?? '';

if ($id && $fecha && $nro_factura && $nit_ci_proveedor && $razon_social && $monto && $tipo_de_compra) {
    $sql = "UPDATE compras SET fecha=?, nro_factura=?, nit_ci_proveedor=?, razon_social=?, monto=?, tipo_de_compra=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssdsi", $fecha, $nro_factura, $nit_ci_proveedor, $razon_social, $monto, $tipo_de_compra, $id);
        $stmt->execute();
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $conn->error]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit;
}
?>