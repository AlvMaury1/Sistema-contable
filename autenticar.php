<?php
session_start();

include 'conexion.inc.php';

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Consulta SQL para obtener el usuario y la contraseña en texto plano
$sql = "SELECT u.id, u.usuario, r.rol, u.password, u.codRol
        FROM usuarios u
        INNER JOIN rol r ON u.codRol = r.codRol
        WHERE u.usuario='$usuario'";

// Ejecutamos la consulta
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Si el usuario existe, obtenemos la fila de resultados
    $fila = mysqli_fetch_assoc($result);

    // Verificar la contraseña en texto plano
    if ($password == $fila['password']) {
        // Si la contraseña es correcta, guardar los datos en la sesión
        $_SESSION['usuario'] = $fila['usuario'];
        $_SESSION['rol'] = $fila['rol'];
        $_SESSION['codRol'] = $fila['codRol'];
        $_SESSION['id_usuario'] = $fila['id']; // <-- AGREGA ESTA LÍNEA

        // Redirigir según el rol
        if ($fila['rol'] === 'contador') {
            header("Location: bandeja.php?rol=contador");
        } elseif ($fila['rol'] === 'cliente') {
            header("Location: bandeja.php?rol=cliente");
        } elseif ($fila['rol'] === 'admin') {
            header("Location: registro_usuarios.php");
        }
        exit;
    } else {
        // Si la contraseña es incorrecta
        echo "Credenciales incorrectas, o no autorizado.";
        echo "<br><a href='login.php'>Volver al Login</a>";
    }
} else {
    // Si el usuario no existe
    echo "Credenciales incorrectas, o no autorizado.";
    echo "<br><a href='login.php'>Volver al Login</a>";
}
?>
