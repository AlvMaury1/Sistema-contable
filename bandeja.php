<?php
session_start();

// Verifica si el usuario está logueado y obtiene su rol
// if (!isset($_SESSION['usuario_id'])) {
//     header("Location: login.php"); // Redirigir al login si no está autenticado
//     exit;
// }

// Obtener el rol del usuario desde la sesión
$codRol = $_SESSION['codRol']; // El rol (1 para contador, 2 para cliente)

include "conexion.inc.php"; // Conexión a la base de datos

// Determinar el flujo y los procesos según el rol
if ($codRol == 1) {
    // Si es contador, se muestra el flujo 'f1'
    $sql = "SELECT * FROM proceso WHERE codflujo = 'f1' AND codRol = 1";
    $titulo = "Bandeja de Entrada - Contador";
    // Obtener clientes
    $clientes = mysqli_query($conn, "SELECT id, usuario FROM usuarios WHERE codRol=2");
} elseif ($codRol == 2) {
    // Si es cliente, se muestra el flujo 'f2'
    $sql = "SELECT * FROM proceso WHERE codflujo = 'f2' AND codRol = 2";
    $titulo = "Bandeja de Entrada - Cliente";

} else {
    // Si el rol no está definido
    die("Acceso no autorizado");
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    function redirigirProceso(url) {
        var select = document.getElementById('selectCliente');
        if (select && select.value) {
            window.location = url + "&id_cliente=" + select.value;
        } else {
            alert("Seleccione un cliente.");
        }
    }
    </script>
</head>
<body class="bg-gradient-to-br from-[#f3f4f6] via-[#e5e7eb] to-[#d1d5db] min-h-screen flex items-center justify-center">
<div class="container mx-auto max-w-2xl">
    <div class="relative bg-white rounded-2xl shadow-2xl border-4 border-[#2563eb] p-10 mt-8 overflow-hidden">
        <!-- Decoración superior -->
        <div class="absolute -top-8 -left-8 w-32 h-32 bg-[#2563eb] opacity-10 rounded-full z-0"></div>
        <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-[#f59e42] opacity-10 rounded-full z-0"></div>
        <div class="relative z-10">
            <h2 class="mb-8 text-3xl font-extrabold text-center text-[#2563eb] tracking-tight drop-shadow-lg">
                <?= $titulo ?>
            </h2>

            <?php if ($codRol == 1): ?>
            <div class="mb-8">
                <label for="selectCliente" class="block mb-2 text-[#1f2937] font-semibold">Seleccionar Cliente:</label>
                <select id="selectCliente" class="w-full px-4 py-3 rounded-xl border-2 border-[#9ca3af] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] text-[#1f2937] bg-[#f3f4f6] transition font-medium shadow-inner hover:border-[#2563eb]">
                    <option value="">-- Seleccione --</option>
                    <?php while($cli = mysqli_fetch_assoc($clientes)): ?>
                        <option value="<?= $cli['id'] ?>"><?= htmlspecialchars($cli['usuario']) ?> (ID: <?= $cli['id'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="flex flex-col gap-5 mt-6">
                <?php
                // Mostrar los procesos disponibles para el rol
                while ($row = mysqli_fetch_assoc($result)) {
                    $codProceso = $row['codProceso'];
                    $codFlujo = $row['codFlujo'];
                    $pantalla = ucfirst(pathinfo($row['pantalla'], PATHINFO_FILENAME)); 
                    if($codRol == 2)
                    {
                    echo "<a href='motor2.php?codflujo=$codFlujo&codproceso=$codProceso' class='group block px-8 py-5 rounded-xl border-2 border-[#2563eb] bg-[#e5e7eb] text-[#1f2937] font-semibold shadow-md hover:bg-[#2563eb] hover:text-white hover:border-[#1e40af] transition-all duration-200 relative overflow-hidden'>
                            <span class=\"absolute left-0 top-0 h-full w-2 bg-[#2563eb] group-hover:w-full group-hover:opacity-10 transition-all duration-300 rounded-l-xl\"></span>
                            <span class=\"relative z-10\">Proc: " . $pantalla . "</span>
                        </a>";

                    }else
                    {
                        // Para contador, pasar id_cliente por JS
                        $url = "motor.php?codflujo=$codFlujo&codproceso=$codProceso";
                        echo "<a href='#' onclick=\"redirigirProceso('$url')\" class='group block px-8 py-5 rounded-xl border-2 border-[#2563eb] bg-[#e5e7eb] text-[#1f2937] font-semibold shadow-md hover:bg-[#2563eb] hover:text-white hover:border-[#1e40af] transition-all duration-200 relative overflow-hidden'>
                                <span class=\"absolute left-0 top-0 h-full w-2 bg-[#2563eb] group-hover:w-full group-hover:opacity-10 transition-all duration-300 rounded-l-xl\"></span>
                                <span class=\"relative z-10\">Proc: " . $pantalla . "</span>
                            </a>";
                    }
                }
                ?>
            </div>

            <div class="mt-10 flex justify-end">
                <a href="logout.php" class="inline-block px-8 py-3 rounded-xl font-bold bg-[#ef4444] text-white shadow-lg hover:bg-[#b91c1c] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#ef4444] focus:ring-offset-2">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
