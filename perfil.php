<?php
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: inicio_sesion.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, contrasena = ? WHERE id = ?");
        $stmt->execute([$nombre, $password, $usuario_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
        $stmt->execute([$nombre, $usuario_id]);
    }

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $foto_perfil = 'uploads/' . basename($_FILES['foto_perfil']['name']);
        move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil);
        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->execute([$foto_perfil, $usuario_id]);
    }

    header('Location: perfil.php');
    exit();
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 p-4 text-white">
        <nav class="flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-gray-300">Inicio</a></li>
                <li><a href="perfil.php" class="hover:text-gray-300">Perfil</a></li>
                <li><a href="cerrar_sesion.php" class="hover:text-gray-300">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-6">
        <h2 class="text-2xl font-semibold mb-4 text-center">Perfil</h2>
        <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
            <form method="POST" action="perfil.php" enctype="multipart/form-data">
                <label for="nombre" class="block text-left text-gray-700">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4">

                <label for="password" class="block text-left text-gray-700">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" id="password" name="password" class="border border-gray-300 rounded w-full py-2 px-3 mb-4">

                <label for="foto_perfil" class="block text-left text-gray-700">Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" class="border border-gray-300 rounded w-full py-2 px-3 mb-4">

                <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 transition duration-300 px-4 py-2 rounded">Guardar Cambios</button>
            </form>
            <?php if ($usuario['foto_perfil']): ?>
                <div class="mt-4">
                    <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="rounded w-24 h-24">
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer class="bg-gray-800 p-4 text-center text-white mt-8">
        <p>&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
