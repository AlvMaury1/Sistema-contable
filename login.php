<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f3f4f6] min-h-screen flex items-center justify-center">
<div class="container mx-auto flex justify-center items-center min-h-screen">
  <div class="bg-white rounded-xl shadow-lg border-2 border-[#2563eb] p-8 w-full max-w-md">
    <h3 class="mb-6 text-center text-2xl font-bold text-[#2563eb]">Iniciar sesión</h3>
    <form method="POST" action="autenticar.php" class="space-y-5">
      <div>
        <input type="text" class="form-control w-full px-4 py-3 rounded-lg border border-[#9ca3af] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] text-[#1f2937] placeholder-[#4b5563] transition" name="usuario" placeholder="Usuario" required>
      </div>
      <div>
        <input type="password" class="form-control w-full px-4 py-3 rounded-lg border border-[#9ca3af] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] text-[#1f2937] placeholder-[#4b5563] transition" name="password" placeholder="Contraseña" required>
      </div>
      <button type="submit" class="btn w-full py-3 rounded-lg font-semibold bg-[#2563eb] text-white shadow-md hover:bg-[#3b82f6] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#1e40af] focus:ring-offset-2">
        Ingresar
      </button>
    </form>
  </div>
</div>
</body>
</html>
