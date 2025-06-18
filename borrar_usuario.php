<?php
include 'conexion.inc.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // No permitir borrar admin (por seguridad)
    $sql = "DELETE FROM usuarios WHERE id = $id AND codRol IN (1,2)";
    mysqli_query($conn, $sql);
}

header("Location: registro_usuarios.php");
exit;
?>