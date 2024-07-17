<?php
session_start();
include 'config.php';
require 'composer/index.php'; // Asegúrate de que la ruta sea correcta
require 'composer/vendor/autoload.php';


if (!isset($_SESSION['usuario_id'])) {
    header('Location: inicio_sesion.php');
    exit();
}

$evento_id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Registrar al usuario en el evento
    $stmt = $pdo->prepare("INSERT INTO registro_eventos (id_usuario, id_evento) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $evento_id]);

    // Obtener datos del evento
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->execute([$evento_id]);
    $evento = $stmt->fetch();

    // Obtener datos del usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();

    // Enviar notificación por correo electrónico usando PHPMailer
    $correo_usuario = $usuario['correo'];
    $asunto = "Registro en el evento: " . $evento['nombre'];
    $cuerpo = "Te has registrado exitosamente en el evento " . $evento['nombre'] . 
              ".<br>Fecha: " . $evento['fecha'] . 
              "<br>Lugar: " . $evento['lugar'] .
              "<br>¡Esperamos verte allí!";

    $resultado = enviarCorreo($correo_usuario, $asunto, $cuerpo); // Llamar a la función para enviar el correo
    echo $resultado;

    header('Location: lista_eventos.php');
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->execute([$evento_id]);
$evento = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Evento</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="cerrar_sesion.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2><?php echo htmlspecialchars($evento['nombre']); ?></h2>
        <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
        <p><?php echo htmlspecialchars($evento['fecha']); ?> a las <?php echo htmlspecialchars($evento['hora']); ?></p>
        <p>En <?php echo htmlspecialchars($evento['lugar']); ?></p>
        <p>Capacidad: <?php echo htmlspecialchars($evento['capacidad']); ?></p>
        <form method="POST" action="ver_evento.php?id=<?php echo $evento['id']; ?>">
            <button type="submit">Registrarse</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Plataforma de Eventos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
