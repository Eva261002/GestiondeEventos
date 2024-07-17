<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 'organizador') {
    header('Location: inicio_sesion.php');
    exit();
}

include 'config.php';

// Consultar información del usuario logueado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario_actual = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $lugar = $_POST['lugar'];
    $capacidad = $_POST['capacidad'];
    $id_organizador = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO eventos (nombre, descripcion, fecha, hora, lugar, capacidad, id_organizador) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $fecha, $hora, $lugar, $capacidad, $id_organizador]);

    header('Location: lista_eventos.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 p-4 text-white shadow">
        <nav class="max-w-7xl mx-auto px-4">
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-orange-500">Inicio</a></li>
                <li><a href="perfil.php" class="hover:text-orange-500">Perfil</a></li>
                <li><a href="cerrar_sesion.php" class="hover:text-orange-500">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Crear Evento</h2>
        
        <div class="mb-4 text-center">
            <p class="text-gray-700">Usuario: <span class="font-medium"><?php echo htmlspecialchars($usuario_actual['nombre']); ?></span></p>
        </div>
        
        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="nombre" class="block text-gray-700">Nombre del Evento:</label>
                <input type="text" id="nombre" name="nombre" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>
            <div>
                <label for="descripcion" class="block text-gray-700">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            <div>
                <label for="fecha" class="block text-gray-700">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>
            <div>
                <label for="hora" class="block text-gray-700">Hora:</label>
                <input type="time" id="hora" name="hora" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>
            <div>
                <label for="lugar" class="block text-gray-700">Lugar:</label>
                <input type="text" id="lugar" name="lugar" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>
            <div>
                <label for="capacidad" class="block text-gray-700">Capacidad:</label>
                <input type="number" id="capacidad" name="capacidad" required class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500" />
            </div>
            <button type="submit" class="w-full p-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-orange-700 transition duration-300 transform hover:scale-105">Crear Evento</button>
        </form>
    </main>
    
    <footer class="mt-10 bg-white shadow">
        <p class="text-center py-4">&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
