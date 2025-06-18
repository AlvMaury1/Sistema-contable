<?php
include 'conexion.inc.php';

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$rol = $_POST['rol'];
$nit = $_POST['nit'];
$razon_social = $_POST['razon_social'];

$sql = "INSERT INTO usuarios (usuario, password, codRol, nit, razon_social) 
        VALUES ('$usuario', '$password', '$rol', '$nit', '$razon_social')";

if (mysqli_query($conn, $sql)) {
    header("Location: registro_usuarios.php?success=true");
    exit;
} else {
    echo "Error al registrar usuario: " . mysqli_error($conn);
}
?>
