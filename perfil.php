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

    // Actualizar la contraseña si se proporciona
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, contrasena = ? WHERE id = ?");
        $stmt->execute([$nombre, $password, $usuario_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
        $stmt->execute([$nombre, $usuario_id]);
    }

    // Manejo de la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_types)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $foto_perfil = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil)) {
                $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
                $stmt->execute([$foto_perfil, $usuario_id]);
                $_SESSION['foto_perfil'] = $foto_perfil;
            }
        }
    }

    header('Location: perfil.php');
    exit();
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch();
$_SESSION['foto_perfil'] = $usuario['foto_perfil'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilo.css">
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('foto_perfil_preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
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
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-semibold mb-2">Foto de Perfil:</h3>
                    <div class="w-32 h-32 mx-auto">
                        <img id="foto_perfil_preview" src="<?php echo isset($_SESSION['foto_perfil']) ? htmlspecialchars($_SESSION['foto_perfil']) : ''; ?>" alt="Foto de Perfil" class="rounded-full object-cover w-full h-full">
                    </div>
                </div>
                <label for="nombre" class="block text-left text-gray-700">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4">

                <label for="password" class="block text-left text-gray-700">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" id="password" name="password" class="border border-gray-300 rounded w-full py-2 px-3 mb-4">

                <label for="foto_perfil" class="block text-left text-gray-700">Foto de Perfil:</label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept=".jpg, .jpeg, .png, .gif" class="border border-gray-300 rounded w-full py-2 px-3 mb-4" onchange="previewImage(event)">

                <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 transition duration-300 px-4 py-2 rounded">Guardar Cambios</button>
            </form>
            
        </div>
    </main>
    <footer class="bg-gray-800 p-4 text-center text-white mt-8">
        <p>&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
