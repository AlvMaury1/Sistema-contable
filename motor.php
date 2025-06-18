<?php
$codFlujo = $_GET["codflujo"];
$codProceso = $_GET["codproceso"];
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : null;

include "conexion.inc.php";

// Proceso actual
$sql = "SELECT * FROM proceso WHERE codproceso='$codProceso' AND codflujo='$codFlujo'";
$result = mysqli_query($conn, $sql);
$fila = mysqli_fetch_array($result);

$codProcesoSiguiente = $fila['codProcesoSiguiente'];
$archivo = $fila['pantalla'];

// Proceso siguiente
$sql_siguiente = "SELECT pantalla FROM proceso WHERE codFlujo='$codFlujo' AND codProceso='$codProcesoSiguiente'";
$result_siguiente = mysqli_query($conn, $sql_siguiente);
$pantalla_siguiente = mysqli_fetch_assoc($result_siguiente)['pantalla'] ?? '';

// Proceso anterior
$sql_anterior = "SELECT pantalla, codProceso FROM proceso WHERE codFlujo='$codFlujo' AND codProcesoSiguiente='$codProceso'";
$result_anterior = mysqli_query($conn, $sql_anterior);
$fila_anterior = mysqli_fetch_assoc($result_anterior);
$pantalla_anterior = $fila_anterior['pantalla'] ?? '';
$codProcesoAnterior = $fila_anterior['codProceso'] ?? '';

// Remover .php
$pantalla_siguiente = ucfirst(pathinfo($pantalla_siguiente, PATHINFO_FILENAME));
$pantalla_anterior = ucfirst(pathinfo($pantalla_anterior, PATHINFO_FILENAME));
?>

<html>
<head>
  <!-- Importar Tailwind CSS desde CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#2563eb',
            'primary-light': '#3b82f6',
            'primary-dark': '#1e40af',
            secondary: '#f59e42',
            'secondary-light': '#fb923c',
            'secondary-dark': '#ea580c',
            success: '#22c55e',
            warning: '#facc15',
            error: '#ef4444',
            info: '#0ea5e9',
            'bg-light': '#f3f4f6',
            'bg-medium': '#e5e7eb',
            'bg-dark': '#d1d5db',
            'text-main': '#1f2937',
            'text-secondary': '#4b5563',
            border: '#9ca3af',
            overlay: 'rgba(31,41,55,0.5)'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-bg-light min-h-screen text-text-main">
<div class="container mx-auto py-8 px-4">

  <div class="bg-white rounded-xl shadow-lg border border-primary p-6 mb-8">
    <?php include $archivo; ?>
  </div>

  <div class="flex justify-between items-center mb-8">
    <?php if ($pantalla_anterior): ?>
    <a href="controlador.php?Anterior=1&codflujo=<?= $codFlujo ?>&codproceso=<?= $codProceso ?>&codprocesosiguiente=<?= $codProcesoAnterior ?>&archivo=<?= $archivo ?><?= $id_cliente ? '&id_cliente=' . $id_cliente : '' ?>"
      class="bg-secondary-dark text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-secondary transition-colors duration-200 text-center">
       <?= htmlspecialchars($pantalla_anterior) ?>
    </a>
    <?php else: ?>
      <span></span>
    <?php endif; ?>

    <a href="bandeja.php"
      class="bg-secondary text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-secondary-light transition-colors duration-200 text-center">
      Volver a la Bandeja de Entrada
    </a>

    <?php if ($pantalla_siguiente): ?>
    <a href="controlador.php?Siguiente=1&codflujo=<?= $codFlujo ?>&codproceso=<?= $codProceso ?>&codprocesosiguiente=<?= $codProcesoSiguiente ?>&archivo=<?= $archivo ?><?= $id_cliente ? '&id_cliente=' . $id_cliente : '' ?>"
      class="bg-primary-dark text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-primary transition-colors duration-200 text-center">
      <?= htmlspecialchars($pantalla_siguiente) ?> 
    </a>
    <?php else: ?>
      <span></span>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
