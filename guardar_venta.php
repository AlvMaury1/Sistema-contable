<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error de conexión");

$id_cliente = $_POST['id_cliente'] ?? $_SESSION['id_usuario'];
$fecha = $_POST['fecha'];
$nro_factura = $_POST['nro_factura'];
$nit_ci = $_POST['nit_ci'];
$razon_social = $_POST['razon_social'];
$monto = $_POST['monto'];
$metodo_pago = $_POST['metodo_pago'];

$sql = "INSERT INTO ventas (id_cliente, fecha, nro_factura, nit_ci, razon_social, monto, metodo_pago)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("issssds", $id_cliente, $fecha, $nro_factura, $nit_ci, $razon_social, $monto, $metodo_pago);
$stmt->execute();

header("Location: motor.php?codflujo=f1&codproceso=p1&id_cliente=$id_cliente");
exit;
?>