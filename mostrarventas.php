<?php
session_start();
if (isset($_SESSION['codRol'])) {
    $codRol = $_SESSION['codRol'];
}
$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error: " . $conexion->connect_error);

$gestion = isset($_POST['gestion']) ? $_POST['gestion'] : '';
$periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$doccliente = isset($_POST['doccliente']) ? $_POST['doccliente'] : '';
$nrofactura = isset($_POST['nrofactura']) ? $_POST['nrofactura'] : '';

// --- FILTRAR POR USUARIO LOGUEADO O SELECCIONADO ---
$id_cliente = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
if ($codRol == 1 && isset($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);
}

$sql = "SELECT * FROM ventas WHERE 1=1";

// Filtrar SIEMPRE por id_cliente si está definido
if ($id_cliente) {
    $sql .= " AND id_cliente = $id_cliente";
}

$meses = [
  "Enero" => 1, "Febrero" => 2, "Marzo" => 3, "Abril" => 4,
  "Mayo" => 5, "Junio" => 6, "Julio" => 7, "Agosto" => 8,
  "Septiembre" => 9, "Octubre" => 10, "Noviembre" => 11, "Diciembre" => 12
];

$mes_numero = isset($meses[$periodo]) ? $meses[$periodo] : null;

// Solo aplicar filtros si se envió el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar'])) {
    if (empty($fecha) && !empty($gestion) && !empty($mes_numero)) {
      $sql .= " AND MONTH(fecha) = $mes_numero AND YEAR(fecha) = $gestion";
    }
    if (!empty($fecha)) {
      $sql .= " AND fecha = '$fecha'";
    }
    if (!empty($doccliente)) {
      $sql .= " AND nit_ci = '$doccliente'";
    }
    if (!empty($nrofactura)) {
      $sql .= " AND nro_factura = '$nrofactura'";
    }
}

$resultado = $conexion->query($sql);
include "modal_nueva_venta.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <h2 class="mb-4">PERIODO SELECCIONADO <?= htmlspecialchars($periodo) ?> <?= htmlspecialchars($gestion) ?></h2>
  <div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Ventas Registradas</h4>
  <?php if ($codRol == 1): ?>  <!-- Solo mostrar el botón si el rol es 'contador' -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaVenta">
    + Nuevo Registro
    </button>
  <?php endif; ?>
</div>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>Fecha de la Factura</th>
          <th>No de la Factura</th>
          <th>NIT/CI Cliente</th>
          <th>Nombre o Razon Social</th>
          <th class="text-end">Monto (Bs)</th>
          <th>Metodo de Pago</th>
          <?php if ($codRol == 1): ?>
            <th>Eliminar</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
      <?php if ($resultado->num_rows > 0): ?>
        <?php while ($venta = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($venta['fecha']) ?></td>
          <td><?= htmlspecialchars($venta['nro_factura']) ?></td>
          <td><?= htmlspecialchars($venta['nit_ci']) ?></td>
          <td><?= htmlspecialchars($venta['razon_social']) ?></td>
          <td class="text-end"><?= number_format($venta['monto'], 2) ?></td>
          <td><?= htmlspecialchars($venta['metodo_pago']) ?></td>
          <?php if ($codRol == 1): ?>
            <td>
              <form method="POST" action="eliminar_venta.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta venta?');">
                <input type="hidden" name="id" value="<?= $venta['id'] ?>">
                <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>
              <button 
                type="button" 
                class="btn btn-warning btn-sm ms-1 btn-editar-venta"
                data-bs-toggle="modal"
                data-bs-target="#modalEditarVenta"
                data-id="<?= $venta['id'] ?>"
                data-fecha="<?= htmlspecialchars($venta['fecha']) ?>"
                data-nro_factura="<?= htmlspecialchars($venta['nro_factura']) ?>"
                data-nit_ci="<?= htmlspecialchars($venta['nit_ci']) ?>"
                data-razon_social="<?= htmlspecialchars($venta['razon_social']) ?>"
                data-monto="<?= htmlspecialchars($venta['monto']) ?>"
                data-metodo_pago="<?= htmlspecialchars($venta['metodo_pago']) ?>"
              >Editar</button>
            </td>
          <?php endif; ?>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7">No se encontraron resultados con esos criterios.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

<!-- Modal Editar Venta -->
<div class="modal fade" id="modalEditarVenta" tabindex="-1" aria-labelledby="modalEditarVentaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="actualizar_venta.php" id="formEditarVenta">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarVentaLabel">Editar Venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editar_id">
          <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" id="editar_fecha" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No Factura</label>
            <input type="text" class="form-control" name="nro_factura" id="editar_nro_factura" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NIT/CI Cliente</label>
            <input type="text" class="form-control" name="nit_ci" id="editar_nit_ci" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Razón Social</label>
            <input type="text" class="form-control" name="razon_social" id="editar_razon_social" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Monto (Bs)</label>
            <input type="number" step="0.01" class="form-control" name="monto" id="editar_monto" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Método de Pago</label>
            <select class="form-select" name="metodo_pago" id="editar_metodo_pago" required>
              <option value="Efectivo">Efectivo</option>
              <option value="Transferencia">Transferencia</option>
              <option value="QR">QR</option>
              <option value="Tarjeta">Tarjeta</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Rellenar el modal con los datos de la venta seleccionada
document.querySelectorAll('.btn-editar-venta').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('editar_id').value = this.dataset.id;
    document.getElementById('editar_fecha').value = this.dataset.fecha;
    document.getElementById('editar_nro_factura').value = this.dataset.nro_factura;
    document.getElementById('editar_nit_ci').value = this.dataset.nit_ci;
    document.getElementById('editar_razon_social').value = this.dataset.razon_social;
    document.getElementById('editar_monto').value = this.dataset.monto;
    // Seleccionar el método de pago correcto (asegura coincidencia exacta)
    let metodoPago = (this.dataset.metodo_pago || '').trim();
    let selectMetodoPago = document.getElementById('editar_metodo_pago');
    Array.from(selectMetodoPago.options).forEach(opt => {
      opt.selected = (opt.value === metodoPago);
    });
    selectMetodoPago.dispatchEvent(new Event('change'));
  });
});
</script>
</body>
</html>
