<?php
session_start();
if (isset($_SESSION['codRol'])) {
    $codRol = $_SESSION['codRol'];
}
$conexion = new mysqli("localhost", "root", "", "sistemacontabilidad");
if ($conexion->connect_error) die("Error: " . $conexion->connect_error);

$gestion = $_POST['gestion'] ?? '';
$periodo = $_POST['periodo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$docproveedor = $_POST['docproveedor'] ?? '';
$nrofactura = $_POST['nrofactura'] ?? '';

$id_cliente = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
if ($codRol == 1 && isset($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);
}

$sql = "SELECT * FROM compras WHERE 1=1";
if ($id_cliente) {
    $sql .= " AND id_cliente = $id_cliente";
}

$meses = [
  "Enero" => 1, "Febrero" => 2, "Marzo" => 3, "Abril" => 4,
  "Mayo" => 5, "Junio" => 6, "Julio" => 7, "Agosto" => 8,
  "Septiembre" => 9, "Octubre" => 10, "Noviembre" => 11, "Diciembre" => 12
];

$mes_numero = $meses[$periodo] ?? null;

// Solo aplicar filtros si se envió el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($fecha) && !empty($gestion) && !empty($mes_numero)) {
      $sql .= " AND MONTH(fecha) = $mes_numero AND YEAR(fecha) = $gestion";
    }
    if (!empty($fecha)) {
      $sql .= " AND fecha = '$fecha'";
    }
    if (!empty($docproveedor)) {
      $sql .= " AND nit_ci_proveedor = '$docproveedor'";
    }
    if (!empty($nrofactura)) {
      $sql .= " AND nro_factura = '$nrofactura'";
    }
}

$resultado = $conexion->query($sql);
include "modal_nueva_compra.php"; 
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
    <h4 class="mb-0">Compras Registradas</h4>
  <?php if ($codRol == 1): ?>  <!-- Solo mostrar el botón si el rol es 'contador' -->
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNuevaCompra">
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
          <th>NIT/CI Proveedor</th>
          <th>Nombre o Razón Social</th>
          <th class="text-end">Monto (Bs)</th>
          <th>Tipo de Compra</th>
          <?php if ($codRol == 1): ?>
            <th>Eliminar</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
      <?php if ($resultado->num_rows > 0): ?>
        <?php while ($compra = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($compra['fecha']) ?></td>
          <td><?= htmlspecialchars($compra['nro_factura']) ?></td>
          <td><?= htmlspecialchars($compra['nit_ci_proveedor']) ?></td>
          <td><?= htmlspecialchars($compra['razon_social']) ?></td>
          <td class="text-end"><?= number_format($compra['monto'], 2) ?></td>
          <td><?= htmlspecialchars($compra['tipo_de_compra']) ?></td>
          <?php if ($codRol == 1): ?>
            <td>
              <form method="POST" action="eliminar_compra.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');">
                <input type="hidden" name="id" value="<?= $compra['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>
              <button 
                type="button" 
                class="btn btn-warning btn-sm ms-1 btn-editar-compra"
                data-bs-toggle="modal"
                data-bs-target="#modalEditarCompra"
                data-id="<?= $compra['id'] ?>"
                data-fecha="<?= htmlspecialchars($compra['fecha']) ?>"
                data-nro_factura="<?= htmlspecialchars($compra['nro_factura']) ?>"
                data-nit_ci_proveedor="<?= htmlspecialchars($compra['nit_ci_proveedor']) ?>"
                data-razon_social="<?= htmlspecialchars($compra['razon_social']) ?>"
                data-monto="<?= htmlspecialchars($compra['monto']) ?>"
                data-tipo_de_compra="<?= htmlspecialchars($compra['tipo_de_compra']) ?>"
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

<!-- Modal Editar Compra -->
<div class="modal fade" id="modalEditarCompra" tabindex="-1" aria-labelledby="modalEditarCompraLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="actualizar_compra.php" id="formEditarCompra">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarCompraLabel">Editar Compra</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editar_id_compra">
          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" id="editar_fecha_compra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No Factura</label>
            <input type="text" class="form-control" name="nro_factura" id="editar_nro_factura_compra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">NIT/CI Proveedor</label>
            <input type="text" class="form-control" name="nit_ci_proveedor" id="editar_nit_ci_proveedor_compra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Razón Social</label>
            <input type="text" class="form-control" name="razon_social" id="editar_razon_social_compra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Monto (Bs)</label>
            <input type="number" step="0.01" class="form-control" name="monto" id="editar_monto_compra" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo de Compra</label>
            <select class="form-select" name="tipo_de_compra" id="editar_tipo_de_compra_compra" required>
              <option value="Con Crédito Fiscal">Con Crédito Fiscal</option>
              <option value="Sin Crédito Fiscal">Sin Crédito Fiscal</option>
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
document.querySelectorAll('.btn-editar-compra').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('editar_id_compra').value = this.dataset.id;
    document.getElementById('editar_fecha_compra').value = this.dataset.fecha;
    document.getElementById('editar_nro_factura_compra').value = this.dataset.nro_factura;
    document.getElementById('editar_nit_ci_proveedor_compra').value = this.dataset.nit_ci_proveedor;
    document.getElementById('editar_razon_social_compra').value = this.dataset.razon_social;
    document.getElementById('editar_monto_compra').value = this.dataset.monto;
    let tipoCompra = (this.dataset.tipo_de_compra || '').trim();
    let selectTipoCompra = document.getElementById('editar_tipo_de_compra_compra');
    Array.from(selectTipoCompra.options).forEach(opt => {
      opt.selected = (opt.value === tipoCompra);
    });
    selectTipoCompra.dispatchEvent(new Event('change'));
  });
});

// AJAX para guardar cambios sin recargar ni cambiar la URL
document.getElementById('formEditarCompra').addEventListener('submit', function(e) {
  e.preventDefault();
  let form = this;
  let formData = new FormData(form);

  fetch('actualizar_compra.php', {
    method: 'POST',
    body: formData
  })
  .then(resp => resp.json())
  .then(data => {
    if (data.success) {
      // Cierra el modal
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarCompra'));
      modal.hide();
      // Opcional: recarga solo la tabla de compras (puedes mejorar esto con AJAX)
      location.reload();
    } else {
      alert(data.message || 'Error al actualizar la compra');
    }
  })
  .catch(() => alert('Error de red al actualizar la compra'));
});
</script>
</body>
</html>
