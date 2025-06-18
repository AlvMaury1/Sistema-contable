<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuarios</title>
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
            // secondary: '#f59e42', // Quitar naranja
            // 'secondary-light': '#fb923c',
            // 'secondary-dark': '#ea580c',
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
<body class="bg-gradient-to-br from-primary-light via-bg-light to-info min-h-screen flex items-center justify-center">
<div class="w-full max-w-2xl mx-auto">
  <div class="bg-white/90 rounded-3xl shadow-2xl border-2 border-primary p-10 flex flex-col gap-8">
    <div class="flex flex-col items-center gap-2">
      <div class="bg-primary-dark rounded-full p-3 mb-2 shadow-lg">
        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <h2 class="text-3xl font-extrabold text-primary-dark text-center tracking-tight">Registro de Usuario</h2>
      <p class="text-text-secondary text-center">Crea una cuenta para acceder al sistema contable</p>
    </div>
    <form id="formRegistro" action="guardar_usuario.php" method="POST" class="flex flex-col gap-6">
      <div>
        <label class="block mb-1 font-semibold text-text-main">Nombre de Usuario</label>
        <input type="text" name="usuario" placeholder="Ej: juanperez" required
          class="w-full rounded-xl border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition placeholder:text-text-secondary bg-bg-light" />
      </div>
      <div>
  <label class="block mb-1 font-semibold text-text-main">NIT</label>
  <input type="text" name="nit" placeholder="Ej: 123456789" required
    class="w-full rounded-xl border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition placeholder:text-text-secondary bg-bg-light" />
</div>

<div>
  <label class="block mb-1 font-semibold text-text-main">Razón Social</label>
  <input type="text" name="razon_social" placeholder="Ej: Empresa XYZ S.R.L." required
    class="w-full rounded-xl border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition placeholder:text-text-secondary bg-bg-light" />
</div>
      <div>
        <label class="block mb-1 font-semibold text-text-main">Contraseña</label>
        <input type="password" name="password" placeholder="Contraseña segura" required
          class="w-full rounded-xl border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition placeholder:text-text-secondary bg-bg-light" />
      </div>
      <div>
        <label class="block mb-1 font-semibold text-text-main">Rol</label>
        <select name="rol" required
          class="w-full rounded-xl border border-border px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light transition text-text-main bg-bg-light">
          <option value="1">Contador</option>
          <option value="2">Cliente</option>
          <option value="3">Administrador</option>
        </select>
      </div>
      <div class="flex flex-col md:flex-row gap-4 mt-2">
        <button type="submit"
          class="w-full md:w-auto bg-primary text-white font-bold px-8 py-2 rounded-xl shadow-lg hover:bg-primary-light transition-colors duration-200">
          Registrar
        </button>
        <a href="login.php"
          class="w-full md:w-auto bg-info text-white font-bold px-8 py-2 rounded-xl shadow-lg hover:bg-primary-light transition-colors duration-200 text-center">
          Salir
        </a>
      </div>
    </form>
    <button class="bg-error text-white font-bold px-8 py-2 rounded-xl shadow-lg hover:bg-red-600 transition-colors duration-200 w-full"
      data-bs-toggle="modal" data-bs-target="#modalBorrarUsuarios">
      Borrar Usuarios
    </button>
  </div>
</div>

<!-- Modal Borrar Usuarios -->
<div class="fixed inset-0 z-50 flex items-center justify-center bg-[rgba(31,41,55,0.5)] hidden" id="modalBorrarUsuarios">
  <div class="bg-white rounded-2xl shadow-2xl border-2 border-error w-full max-w-3xl">
    <div class="flex justify-between items-center px-8 py-6 border-b border-border">
      <h5 class="text-2xl font-bold text-error" id="modalBorrarUsuariosLabel">Borrar Usuarios (Clientes y Contadores)</h5>
      <button type="button" class="text-gray-400 hover:text-error text-3xl font-bold focus:outline-none" onclick="document.getElementById('modalBorrarUsuarios').classList.add('hidden')">&times;</button>
    </div>
    <div class="p-8 overflow-x-auto">
      <table class="min-w-full bg-bg-light rounded-lg overflow-hidden shadow">
        <thead>
          <tr>
            <th class="px-4 py-2 border-b border-border text-left font-semibold text-text-main">ID</th>
            <th class="px-4 py-2 border-b border-border text-left font-semibold text-text-main">Usuario</th>
            <th class="px-4 py-2 border-b border-border text-left font-semibold text-text-main">Rol</th>
            <th class="px-4 py-2 border-b border-border text-left font-semibold text-text-main">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
          include 'conexion.inc.php';
          $usuarios = mysqli_query($conn, "SELECT u.id, u.usuario, r.rol FROM usuarios u INNER JOIN rol r ON u.codRol = r.codRol WHERE u.codRol IN (1,2)");
          while ($row = mysqli_fetch_assoc($usuarios)): ?>
            <tr class="hover:bg-bg-dark transition">
              <td class="px-4 py-2 border-b border-border"><?= $row['id'] ?></td>
              <td class="px-4 py-2 border-b border-border"><?= htmlspecialchars($row['usuario']) ?></td>
              <td class="px-4 py-2 border-b border-border"><?= htmlspecialchars($row['rol']) ?></td>
              <td class="px-4 py-2 border-b border-border">
                <form method="POST" action="borrar_usuario.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas borrar este usuario?');">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit"
                    class="bg-error text-white px-4 py-1 rounded-lg font-semibold shadow hover:bg-red-600 transition-colors duration-200 text-sm">
                    Borrar
                  </button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal JS (Tailwind, no Bootstrap) -->
<script>
  document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById(this.getAttribute('data-bs-target').replace('#','')).classList.remove('hidden');
    });
  });
</script>

</body>
</html>
