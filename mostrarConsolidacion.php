<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error: " . $conexion->connect_error);

$gestion = isset($_POST['gestion']) ? $_POST['gestion'] : '';
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';

$id_cliente = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$codRol = isset($_SESSION['codRol']) ? $_SESSION['codRol'] : null;
if ($codRol == 1 && isset($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);
}

$meses = [
  "Enero" => 1, "Febrero" => 2, "Marzo" => 3, "Abril" => 4,
  "Mayo" => 5, "Junio" => 6, "Julio" => 7, "Agosto" => 8,
  "Septiembre" => 9, "Octubre" => 10, "Noviembre" => 11, "Diciembre" => 12
];

$mes_numero = isset($meses[$periodo]) ? $meses[$periodo] : null;

// Filtro por id_cliente SIEMPRE que esté definido
$filtroVentas = "";
$filtroCompras = "";
if ($id_cliente) {
    $filtroVentas = " WHERE id_cliente = $id_cliente";
    $filtroCompras = " WHERE id_cliente = $id_cliente";
}

if (!empty($gestion) && !empty($mes_numero)) {
    $whereVentas = "WHERE MONTH(fecha) = $mes_numero AND YEAR(fecha) = $gestion";
    $whereCompras = "WHERE MONTH(fecha) = $mes_numero AND YEAR(fecha) = $gestion";
    if ($id_cliente) {
        $whereVentas .= " AND id_cliente = $id_cliente";
        $whereCompras .= " AND id_cliente = $id_cliente";
    }
    $sqlVentas = "SELECT SUM(monto) as suma, COUNT(*) as cuenta FROM ventas $whereVentas";
    $sqlCompras = "SELECT SUM(monto) as suma, COUNT(*) as cuenta FROM compras $whereCompras";
} else {
    $sqlVentas = "SELECT SUM(monto) as suma, COUNT(*) as cuenta FROM ventas $filtroVentas";
    $sqlCompras = "SELECT SUM(monto) as suma, COUNT(*) as cuenta FROM compras $filtroCompras";
}

$rowVentas = $conexion->query($sqlVentas)->fetch_assoc();
$rowCompras = $conexion->query($sqlCompras)->fetch_assoc();

$sumaVentas = $rowVentas['suma'] ?? 0.0;
$sumaCompras = $rowCompras['suma'] ?? 0.0;
$cantidadV = $rowVentas['cuenta'] ?? 0;
$cantidadC = $rowCompras['cuenta'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <h2 class="mb-4">CONSOLIDACION: <?= htmlspecialchars($periodo) ?> <?= htmlspecialchars($gestion) ?></h2>
    <p>Cantidad Facturas Ventas: <?= htmlspecialchars($cantidadV) ?></p>
    <p>Total Ventas Bs: <?= htmlspecialchars($sumaVentas) ?></p>
    <p>Cantidad Facturas Compras:  <?= htmlspecialchars($cantidadC) ?></p>
    <p>Total Compras Bs: <?= htmlspecialchars($sumaCompras) ?></p>
  <?php if ($codRol == 1): ?>  

<form method="POST" action="consolidar_periodo.php" target="_blank">
  <input type="hidden" name="gestion" value="<?= htmlspecialchars($gestion) ?>">
  <input type="hidden" name="periodo" value="<?= htmlspecialchars($periodo) ?>">
  <input type="hidden" name="mes_numero" value="<?= htmlspecialchars($mes_numero) ?>">
  <input type="hidden" name="sumaVentas" value="<?= htmlspecialchars($sumaVentas) ?>">
  <input type="hidden" name="sumaCompras" value="<?= htmlspecialchars($sumaCompras) ?>">
  <input type="hidden" name="cantidadV" value="<?= htmlspecialchars($cantidadV) ?>">
  <input type="hidden" name="cantidadC" value="<?= htmlspecialchars($cantidadC) ?>">
  <input type="hidden" name="idcliente" value="<?= htmlspecialchars($id_cliente) ?>">

  <div class="col-md-6 d-grid">
    <button type="submit" name="consolidar" class="btn btn-success mt-4">Consolidar Periodo</button>
  </div>
</form>

<form method="POST" action="eliminar_consolidacion.php" target="_blank" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta consolidación?');">
  <input type="hidden" name="gestion" value="<?= htmlspecialchars($gestion) ?>">
  <input type="hidden" name="periodo" value="<?= htmlspecialchars($periodo) ?>">
  <input type="hidden" name="idcliente" value="<?= htmlspecialchars($id_cliente) ?>">

  <div class="col-md-6 d-grid">
    <button type="submit" class="btn btn-danger mt-2">Eliminar Consolidación</button>
  </div>
</form>
  <?php endif; ?>
<?php if ($codRol == 2): ?>

  <form method="POST" action="ver_consolidacion.php" target="_blank">
    <input type="hidden" name="gestion" value="<?= htmlspecialchars($gestion) ?>">
    <input type="hidden" name="periodo" value="<?= htmlspecialchars($periodo) ?>">
    <input type="hidden" name="idcliente" value="<?= htmlspecialchars($id_cliente) ?>">
    <div class="col-md-6 d-grid">
      <button type="submit" class="btn btn-info mt-4">Ver Consolidación</button>
    </div>
  </form>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
