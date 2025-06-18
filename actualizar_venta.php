<?php
session_start();
if (!isset($_SESSION['codRol']) || $_SESSION['codRol'] != 1) {
    header("Location: login.php");
    exit;
}
include 'conexion.inc.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$nro_factura = isset($_POST['nro_factura']) ? $_POST['nro_factura'] : '';
$nit_ci = isset($_POST['nit_ci']) ? $_POST['nit_ci'] : '';
$razon_social = isset($_POST['razon_social']) ? $_POST['razon_social'] : '';
$monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : '';

if ($id && $id_cliente && $fecha && $nro_factura && $nit_ci && $razon_social && $monto && $metodo_pago) {
    $sql = "UPDATE ventas SET fecha=?, nro_factura=?, nit_ci=?, razon_social=?, monto=?, metodo_pago=? WHERE id=? AND id_cliente=?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssdsii", $fecha, $nro_factura, $nit_ci, $razon_social, $monto, $metodo_pago, $id, $id_cliente);
        mysqli_stmt_execute($stmt);
        header("Location: motor.php?codflujo=f1&codproceso=p1&id_cliente=$id_cliente");
        exit;
    } else {
        echo "Error en la preparación de la consulta: " . mysqli_error($conn);
    }
} else {
    echo "Faltan datos obligatorios.";
}
?>