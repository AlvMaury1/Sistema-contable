<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error de conexión: " . $conexion->connect_error);

// Obtener los datos del POST
$gestion = $_POST['gestion'] ?? '';
$periodo = $_POST['periodo'] ?? '';
$id_cliente = $_POST['idcliente'] ?? ($_SESSION['id_usuario'] ?? null);

// Validación mínima
if (!$gestion || !$periodo || !$id_cliente) {
    echo "Faltan datos para procesar la solicitud.";
    exit;
}

// Buscar la consolidación
$sql = "SELECT id FROM consolidaciones WHERE gestion = ? AND periodo = ? AND id_cliente = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isi", $gestion, $periodo, $id_cliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $consolidacion_id = $fila['id'];

    // Construir la ruta al archivo PDF
    $pdf_filename = "Consolidacion_{$consolidacion_id}_{$periodo}_{$gestion}_cliente{$id_cliente}.pdf";
    $pdf_path = "pdfs/" . $pdf_filename;

    if (file_exists($pdf_path)) {
        // Redirigir al PDF
        header("Location: $pdf_path");
        exit;
    } else {
        echo "La consolidación existe, pero el archivo PDF no fue encontrado.";
    }
} else {
    echo "No existe una consolidación registrada para el cliente, gestión y periodo especificados.";
}
?>
