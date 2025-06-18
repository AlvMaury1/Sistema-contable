<?php
session_start();
if (!isset($_SESSION['codRol']) || $_SESSION['codRol'] != 1) {
    header("Location: login.php");
    exit;
}
include 'conexion.inc.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM ventas WHERE id = $id";
    mysqli_query($conn, $sql);
}

$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : '';
header("Location: motor.php?codflujo=f1&codproceso=p1&id_cliente=$id_cliente");
exit;
?>