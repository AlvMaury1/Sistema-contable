<div class="container py-4">

  <h2 class="mb-4">Registro de Ventas</h2>

  <form method="POST"  class="row g-3 mb-5">

    <div class="col-md-6">
      <label class="form-label">Gestion:</label>
      <select name="gestion" class="form-select" required>
        <option value="2025">2025</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
        <option value="2022">2022</option>
        <option value="2021">2021</option>
        <option value="2020">2020</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Periodo:</label>
      <select name="periodo" class="form-select" required>
        <option value="Enero">Enero</option>
        <option value="Febrero">Febrero</option>
        <option value="Marzo">Marzo</option>
        <option value="Abril">Abril</option>
        <option value="Mayo">Mayo</option>
        <option value="Junio">Junio</option>
        <option value="Julio">Julio</option>
        <option value="Agosto">Agosto</option>
        <option value="Septiembre">Septiembre</option>
        <option value="Octubre">Octubre</option>
        <option value="Noviembre">Noviembre</option>
        <option value="Diciembre">Diciembre</option>

      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Fecha Emision:</label>
      <input type="date" name="fecha" class="form-control" >
    </div>
    <div class="col-md-4">
      <label class="form-label">Nro. Documento Cliente:</label>
      <input type="text" name="doccliente" class="form-control" >
    </div>
        <div class="col-md-4">
      <label class="form-label">Nro. Factura</label>
      <input type="text" name="nrofactura" class="form-control" >
    </div>

    <div class="col-md-6 d-grid">
      <button type="submit" name = "buscar" class="btn btn-primary mt-4">Buscar</button>
    </div>
  </form>

<?php
// SIEMPRE incluir la tabla, no solo al buscar
include "mostrarventas.php";
?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

