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
            background-color: #f0f4f8;
            font-family: 'Roboto', sans-serif;
            background-image: url('https://source.unsplash.com/1600x900/?events');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        header {
            background-color: rgba(30, 41, 59, 0.9);
        }
        .nav-link {
            transition: background-color 0.3s, transform 0.3s;
            color: #ffffff;
        }
        .nav-link:hover {
            background-color: #3B82F6;
            transform: scale(1.05);
        }
        .main-button {
            transition: background-color 0.3s, transform 0.3s;
            background-color: #4F46E5;
        }
        .main-button:hover {
            background-color: #4338CA;
            transform: scale(1.05);
        }
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header class="py-4 shadow-md rounded">
        <nav class="container mx-auto flex justify-between items-center">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="nav-link px-3 py-2 rounded flex items-center">Inicio</a></li>
                <li><a href="registro.php" class="nav-link px-3 py-2 rounded flex items-center">Registrarse</a></li>
                <li><a href="inicio_sesion.php" class="nav-link px-3 py-2 rounded flex items-center">Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto mt-10 text-center bg-white bg-opacity-80 p-8 rounded-lg shadow-lg">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido a la Plataforma de Salón de Eventos "Carola"</h1>
        <p class="text-lg text-gray-700 mb-6">Explora eventos interesantes y únete a la comunidad.</p>
        
        <div class="mb-6">
            <img src="img/Eventcaro.jpg" alt="Bienvenida" class="w-full h-64 object-cover rounded-lg shadow-lg">
        </div>
        
        <?php if ($isLoggedIn): ?>
            <a href="lista_eventos.php" class="main-button text-white px-6 py-3 rounded shadow-lg">Ver Eventos</a>
        <?php else: ?>
            <a href="inicio_sesion.php" class="bg-gray-600 text-white main-button px-6 py-3 rounded shadow-lg">Ver Más Eventos</a>
        <?php endif; ?>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow card">
                <img src="img/ev1.jpg" alt="Evento 1" class="w-full h-40 object-cover mb-4 rounded-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Matrimonio de E&K</h2>
                <p class="text-gray-600">Ven y celebra con nosotros.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow card">
                <img src="img/ev2.jpg" alt="Evento 2" class="w-full h-40 object-cover mb-4 rounded-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-2">15 años de Liz Dariana</h2>
                <p class="text-gray-600">No faltes!</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow card">
                <img src="img/ev3.jpg" alt="Evento 3" class="w-full h-40 object-cover mb-4 rounded-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Graduación</h2>
                <p class="text-gray-600">Ven y celebra nuestro último encuentro.</p>
            </div>
        </div>
        
        <!-- Información del salón de eventos -->
        <div class="mt-12 p-8 bg-white bg-opacity-80 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Información del Salón</h2>
            <p class="text-gray-700 mb-2">Atendemos todo tipo de acontecimiento social: Matrimonios, Bautizos, Cumpleaños, 15 años, Baby shower, Promociones.</p>
            <p class="text-gray-700 mb-2">Dirección: Av. Circunvalación, Ciudad Trinidad, Bolivia.</p>
            <p class="text-gray-700 mb-2">Teléfono: 72814065</p>
            <p class="text-gray-700">¡Contáctanos para más información y reservas!</p>
        </div>
    </main>
    <footer class="py-4 bg-gray-800 rounded shadow-md mt-10">
        <p class="text-center text-white">&copy; 2024 Plataforma de Eventos "Carola". Todos los derechos reservados.</p>
    </footer>
</body>
</html>
