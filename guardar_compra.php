<?php
session_start();
var_dump($_POST['id_cliente']); // <-- Solo para depuración

$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error de conexión");

$id_cliente = $_POST['id_cliente'] ?? $_SESSION['id_usuario'];
$fecha = $_POST['fecha'];
$nro_factura = $_POST['nro_factura'];
$nit_ci_proveedor = $_POST['nit_ci_proveedor'];
$razon_social = $_POST['razon_social'];
$monto = $_POST['monto'];
$tipo_de_compra = $_POST['tipo_de_compra'];

// Insertar en la tabla compras
$sql = "INSERT INTO compras (id_cliente, fecha, nro_factura, nit_ci_proveedor, razon_social, monto, tipo_de_compra)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isssdss", $id_cliente, $fecha, $nro_factura, $nit_ci_proveedor, $razon_social, $monto, $tipo_de_compra);
$stmt->execute();

header("Location: motor.php?codflujo=f1&codproceso=p2&id_cliente=$id_cliente");
exit;
?>
