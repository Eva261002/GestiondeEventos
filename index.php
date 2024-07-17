<?php
session_start();
$isLoggedIn = isset($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #FFEDD5;
            font-family: 'Roboto', sans-serif;
            background-image: url('https://source.unsplash.com/1600x900/?events');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        header {
            background-color: rgba(31, 41, 55, 0.85); /* Fondo con transparencia */
        }
        .nav-link {
            transition: background-color 0.3s, transform 0.3s;
        }
        .nav-link:hover {
            background-color: #FB923C;
            transform: scale(1.1);
        }
        .main-button {
            transition: background-color 0.3s, transform 0.3s;
        }
        .main-button:hover {
            background-color: #2563EB;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <header class="py-4 shadow-md rounded">
        <nav class="container mx-auto flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="text-white nav-link px-3 py-2 rounded flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M10 20l6-6H4l6 6z" /></svg>Inicio</a></li>
                <li><a href="registro.php" class="text-white nav-link px-3 py-2 rounded flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M10 20l6-6H4l6 6z" /></svg>Registrarse</a></li>
                <li><a href="inicio_sesion.php" class="text-white nav-link px-3 py-2 rounded flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path d="M10 20l6-6H4l6 6z" /></svg>Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto mt-10 text-center bg-white bg-opacity-80 p-8 rounded shadow-lg">
        <h1 class="text-4xl font-bold text-gray-800 mb-4 transition transform hover:scale-105">Bienvenido a la Plataforma de Eventos</h1>
        <p class="text-lg text-gray-700 mb-6">Explora eventos interesantes y únete a la comunidad.</p>
        <?php if ($isLoggedIn): ?>
            <a href="lista_eventos.php" class="bg-blue-600 text-white main-button px-6 py-3 rounded shadow-lg">Ver Eventos</a>
        <?php else: ?>
            <a href="inicio_sesion.php" class="bg-gray-600 text-white main-button px-6 py-3 rounded shadow-lg">Iniciar Sesión para Ver Eventos</a>
        <?php endif; ?>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded shadow hover:shadow-lg transform hover:scale-105 transition duration-300">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Evento 1</h2>
                <p class="text-gray-600">Descripción breve del evento 1.</p>
            </div>
            <div class="bg-white p-6 rounded shadow hover:shadow-lg transform hover:scale-105 transition duration-300">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Evento 2</h2>
                <p class="text-gray-600">Descripción breve del evento 2.</p>
            </div>
            <div class="bg-white p-6 rounded shadow hover:shadow-lg transform hover:scale-105 transition duration-300">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Evento 3</h2>
                <p class="text-gray-600">Descripción breve del evento 3.</p>
            </div>
        </div>
    </main>
    <footer class="py-4 bg-gray-800 rounded shadow-md mt-10">
        <p class="text-center text-white">&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
