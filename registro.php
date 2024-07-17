<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = htmlspecialchars($_POST['rol'], ENT_QUOTES, 'UTF-8');
    
    $foto_perfil = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }
        
        $foto_perfil = $uploads_dir . '/' . basename($_FILES['foto_perfil']['name']);
        
        // Validar el tipo de archivo
        $tipo_archivo = pathinfo($foto_perfil, PATHINFO_EXTENSION);
        $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($tipo_archivo), $tipos_permitidos)) {
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $foto_perfil)) {
                // Archivo subido correctamente
            } else {
                echo "<script>alert('Error al mover el archivo subido.');</script>";
                exit();
            }
        } else {
            echo "<script>alert('Formato de imagen no permitido. Solo se permiten JPG, PNG y GIF.');</script>";
            exit();
        }
    }

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, correo, contrasena, foto_perfil, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $contrasena, $foto_perfil, $rol]);

    header('Location: inicio_sesion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FFEDD5;
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
    <header class="py-4 shadow-md bg-gray-800 rounded">
        <nav class="container mx-auto flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded">Inicio</a></li>
                <li><a href="registro.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded">Registrarse</a></li>
                <li><a href="inicio_sesion.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded">Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto mt-10 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Registro</h2>
        <form method="POST" action="registro.php" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-sm mx-auto">
            <label for="nombre" class="block text-left text-gray-700">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
            
            <label for="correo" class="block text-left text-gray-700">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
            
            <label for="password" class="block text-left text-gray-700">Contraseña:</label>
            <input type="password" id="password" name="password" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
            
            <label for="rol" class="block text-left text-gray-700">Rol:</label>
            <select id="rol" name="rol" class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="asistente">Asistente</option>
                <option value="organizador">Organizador</option>
            </select>
            
            <label for="foto_perfil" class="block text-left text-gray-700">Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept=".jpg, .jpeg, .png, .gif" class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">
            
            <button type="submit" class="bg-blue-600 text-white hover:bg-orange-700 transition duration-300 px-4 py-2 rounded shadow-lg">Registrarse</button>
        </form>
    </main>
    <footer class="py-4 bg-gray-800 rounded shadow-md mt-10">
        <p class="text-center text-white">&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>g
</html>
