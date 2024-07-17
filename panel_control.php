<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: inicio_sesion.php');
    exit();
}

include 'config.php';

$usuario_id = $_SESSION['usuario_id'];
$usuario_rol = $_SESSION['usuario_rol'];

// info de usuario registrado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario_actual = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 p-4 text-white shadow-lg">
        <nav class="flex justify-between items-center">
            <div>
                <a href="index.php" class="text-lg font-semibold hover:text-gray-300">Inicio</a>
            </div>
            <div>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="perfil.php" class="mr-4 hover:text-gray-300">Perfil</a>
                    <a href="cerrar_sesion.php" class="hover:text-gray-300">Cerrar Sesión</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-6">
        <h2 class="text-3xl font-semibold mb-4 text-center text-gray-800">Bienvenido al Panel de Control</h2>
        
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg mb-6 text-center">
            <h3 class="text-2xl font-semibold mb-2 text-gray-700">Perfil de Usuario</h3>
            <p class="mb-2 text-gray-600">Nombre: <span class="font-medium"><?php echo htmlspecialchars($usuario_actual['nombre']); ?></span></p>
            <p class="mb-2 text-gray-600">Rol: <span class="font-medium"><?php echo htmlspecialchars($usuario_rol); ?></span></p>
        </div>
        
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg mb-6 text-center">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Acciones Disponibles</h3>
            <?php if ($usuario_rol == 'organizador'): ?>
                <p class="mb-2"><a href="crear_evento.php" class="text-blue-500 hover:text-blue-700">Crear un Nuevo Evento</a></p>
                <p class="mb-2"><a href="lista_eventos.php" class="text-blue-500 hover:text-blue-700">Ver mis Eventos</a></p>
            <?php else: ?>
                <p class="mb-2"><a href="lista_eventos.php" class="text-blue-500 hover:text-blue-700">Ver Eventos Disponibles</a></p>
            <?php endif; ?>
        </div>
    </main>
    <footer class="bg-gray-800 p-4 text-center text-white mt-8">
        <p>&copy; Plataforma de Eventos "Carola". Todos los derechos reservados.</p>
    </footer>
</body>
</html>
