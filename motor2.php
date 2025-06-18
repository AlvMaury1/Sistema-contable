<?php
$codFlujo = 'f2'; // Aseguramos que el flujo sea siempre f2 para el cliente
$codProceso = $_GET["codproceso"];
$codRol = 2; // 2 es el código para el cliente

include "conexion.inc.php";

// Proceso actual
$sql = "SELECT * FROM proceso WHERE codproceso='$codProceso' AND codflujo='$codFlujo' AND codRol='$codRol'";
$result = mysqli_query($conn, $sql);
$fila = mysqli_fetch_array($result);

$codProcesoSiguiente = $fila['codProcesoSiguiente'];
$pantalla = $fila['pantalla'];

// Proceso siguiente
$sql_siguiente = "SELECT pantalla FROM proceso WHERE codFlujo='$codFlujo' AND codProceso='$codProcesoSiguiente' AND codRol='$codRol'";
$result_siguiente = mysqli_query($conn, $sql_siguiente);
$pantalla_siguiente = mysqli_fetch_assoc($result_siguiente)['pantalla'] ?? '';

// Proceso anterior
$sql_anterior = "SELECT pantalla, codProceso FROM proceso WHERE codFlujo='$codFlujo' AND codProcesoSiguiente='$codProceso' AND codRol='$codRol'";
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
  <h2 class="mb-8 text-3xl font-bold text-primary-dark tracking-tight">CONSULTAS</h2>

  <!-- Formulario de Búsqueda -->
  <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-white p-6 rounded-xl shadow-lg border border-primary">
    <div>
      <label class="block mb-2 font-semibold text-text-main">Gestion:</label>
      <select name="gestion" class="form-select w-full rounded-lg border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition" required>
        <option value="2025">2025</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
        <option value="2022">2022</option>
        <option value="2021">2021</option>
        <option value="2020">2020</option>
      </select>
    </div>

    <div>
      <label class="block mb-2 font-semibold text-text-main">Periodo:</label>
      <select name="periodo" class="form-select w-full rounded-lg border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition" required>
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

    <div class="md:col-span-2 flex flex-col md:flex-row gap-4 mt-2">
      <button type="submit" name="buscar" class="w-full md:w-auto bg-primary text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-primary-light transition-colors duration-200">
        Buscar
      </button>
      <a href="bandeja.php" class="w-full md:w-auto bg-secondary text-white font-semibold px-6 py-2 rounded-lg shadow hover:bg-secondary-light transition-colors duration-200 text-center">
        Volver a la Bandeja de Entrada
      </a>
    </div>
  </form>

  <!-- Botones de navegación -->
  <div class="flex justify-between items-center mb-8">
    <a href="motor2.php?codflujo=f2&codproceso=<?php echo $codProcesoAnterior; ?>"
      class="bg-secondary-dark text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-secondary transition-colors duration-200">
      <?= htmlspecialchars($pantalla_anterior) ?>
    </a>
    <a href="motor2.php?codflujo=f2&codproceso=<?php echo $codProcesoSiguiente; ?>"
      class="bg-primary-dark text-white px-5 py-2 rounded-lg font-semibold shadow hover:bg-primary transition-colors duration-200">
      <?= htmlspecialchars($pantalla_siguiente) ?>
    </a>
  </div>

  <!-- Pantallas dinámicas -->
  <div class="bg-bg-medium rounded-xl p-6 shadow-inner border border-border">
    <?php
      include "$pantalla"; // Esto incluye la pantalla dinámica
    ?>
  </div>
</div>
</body>
</html>

<?php
// Capturamos los valores de gestión y periodo seleccionados en el formulario
if (isset($_POST['buscar'])) {
    $gestion = $_POST['gestion'];
    $periodo = $_POST['periodo'];


} else {
    // Si no se envía formulario, usamos los valores predeterminados
    $gestion = date("Y");  // Usamos el año actual como valor predeterminado
    $periodo = "Enero";    // Usamos "Enero" como valor predeterminado
}
    // Si se seleccionan valores de periodo y gestión, filtramos los resultados en las pantallas correspondientes
    if ($pantalla == "mostrarventas") {
        // Aquí va el código para filtrar las ventas según gestión y periodo
        echo "Mostrando ventas de la gestión $gestion para el periodo $periodo";
    } elseif ($pantalla == "mostrarcompras") {
        // Aquí va el código para filtrar las compras según gestión y periodo
        echo "Mostrando compras de la gestión $gestion para el periodo $periodo";
    } elseif ($pantalla == "mostrarconsolidaciones") {
        // Aquí va el código para mostrar la consolidación correspondiente a la gestión y periodo
        echo "Mostrando consolidaciones de la gestión $gestion para el periodo $periodo";
    }
?>
