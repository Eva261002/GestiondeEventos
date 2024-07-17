<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        header('Location: panel_control.php');
        exit();
    } else {
        echo "<p class='text-red-500 text-center'>Correo o contraseña incorrectos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FFEDD5; /* Color de fondo similar al fuego */
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
    <header class="py-4 shadow-md bg-gray-800 rounded">
        <nav class="container mx-auto flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded transform hover:scale-105">Inicio</a></li>
                <li><a href="registro.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded transform hover:scale-105">Registrarse</a></li>
                <li><a href="inicio_sesion.php" class="text-white hover:bg-orange-500 transition duration-300 px-3 py-2 rounded transform hover:scale-105">Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto mt-10 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Inicio de Sesión</h2>
        <form method="POST" action="" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-sm mx-auto">
            <label for="correo" class="block text-left text-gray-700">Correo:</label>
            <input type="email" id="correo" name="correo" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">

            <label for="contrasena" class="block text-left text-gray-700">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required class="border border-gray-300 rounded w-full py-2 px-3 mb-4 focus:outline-none focus:ring-2 focus:ring-orange-500">

            <button type="submit" class="bg-gray-600 text-white hover:bg-orange-700 transition duration-300 px-4 py-2 rounded shadow-lg transform hover:scale-105">Iniciar Sesión</button>
        </form>
    </main>
    <footer class="py-4 bg-gray-800 rounded shadow-md mt-10">
        <p class="text-center text-white">&copy; Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
