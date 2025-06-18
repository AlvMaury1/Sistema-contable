<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error: " . $conexion->connect_error);

$gestion = $_POST['gestion'] ?? '';
$periodo = $_POST['periodo'] ?? '';
$id_cliente = $_POST['idcliente'] ?? ($_SESSION['id_usuario'] ?? null);

if (!$gestion || !$periodo || !$id_cliente) {
    exit("Datos incompletos.");
}

// Buscar consolidación
$sql = "SELECT id FROM consolidaciones WHERE gestion = ? AND periodo = ? AND id_cliente = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isi", $gestion, $periodo, $id_cliente);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $consolidacion_id = $row['id'];

    // Eliminar registro
    $stmt_del = $conexion->prepare("DELETE FROM consolidaciones WHERE id = ?");
    $stmt_del->bind_param("i", $consolidacion_id);
    $stmt_del->execute();

    // Eliminar PDF
    $pdf_path = "pdfs/Consolidacion_{$consolidacion_id}_{$periodo}_{$gestion}_cliente{$id_cliente}.pdf";
    if (file_exists($pdf_path)) {
        unlink($pdf_path);
    }

    echo "Consolidación eliminada correctamente.";
} else {
    echo "No existe una consolidación para los datos proporcionados.";
}
?>
