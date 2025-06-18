<!-- modal_nueva_venta.php -->
<div class="modal fade" id="modalNuevaVenta" tabindex="-1" aria-labelledby="modalNuevaVentaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" action="guardar_venta.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevaVentaLabel">Nueva Venta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body row g-3">
         <div class="col-md-6">
  <label class="form-label">Fecha:</label>
  <input type="date" name="fecha" class="form-control" required>
</div>

<div class="col-md-6">
  <label class="form-label">Nro. Factura:</label>
  <input type="text" name="nro_factura" class="form-control" required>
</div>

<div class="col-md-6">
  <label class="form-label">NIT/CI Cliente:</label>
  <input type="text" name="nit_ci" class="form-control" required>
</div>

<div class="col-md-6">
  <label class="form-label">Nombre o Razón Social:</label>
  <input type="text" name="razon_social" class="form-control" required>
</div>

<div class="col-md-6">
  <label class="form-label">Monto (Bs):</label>
  <input type="number" name="monto" class="form-control" step="0.01" required>
</div>

<div class="col-md-6">
  <label class="form-label">Método de Pago:</label>
  <select name="metodo_pago" class="form-select" required>
    <option value="Efectivo">Efectivo</option>
    <option value="Transferencia">Transferencia</option>
    <option value="QR">QR</option>
  </select>
</div>

<input type="hidden" name="id_cliente" value="<?= isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : (isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : '') ?>">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar venta</button>
        </div>
      </form>
    </div>
  </div>
</div>
