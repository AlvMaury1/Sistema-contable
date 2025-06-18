<?php
// Validar existencia de las variables antes de asignar
$codFlujo = $_GET["codflujo"] ?? null;
$codProceso = $_GET["codproceso"] ?? null;
$codProcesoSiguiente = $_GET["codprocesosiguiente"] ?? null;
$archivo = $_GET["archivo"] ?? null;
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : null;

if (!$codFlujo || !$codProceso || !$archivo) {
  die("Error: Parámetros faltantes en el controlador.");
}

include "conexion.inc.php";

if (isset($_GET["Anterior"])) {
  $sql = "SELECT * FROM proceso WHERE codflujo='$codFlujo' AND codProcesoSiguiente='$codProceso'";
} elseif (isset($_GET["Siguiente"])) {
  $sql = "SELECT * FROM proceso WHERE codflujo='$codFlujo' AND codProceso='$codProcesoSiguiente'";
} else {
  die("Acción no válida.");
}

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
  die("No se encontró el proceso solicitado.");
}

$fila = mysqli_fetch_array($result);
$codprocesoEnvia = $fila['codProceso'];
$archivoEnvia = "motor.php?codflujo=$codFlujo&codproceso=$codprocesoEnvia";

// Mantener id_cliente si existe
if ($id_cliente) {
  $archivoEnvia .= "&id_cliente=$id_cliente";
}

header("Location: $archivoEnvia");
exit;
?>
