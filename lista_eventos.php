<?php
session_start();
include 'config.php';
require 'composer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['usuario_id'])) {
    header('Location: inicio_sesion.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consultar información del usuario logueado
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$usuario_actual = $stmt->fetch();

// Consultar eventos a los que el usuario NO está registrado
$stmt = $pdo->prepare("SELECT e.* FROM eventos e 
                       LEFT JOIN registro_eventos re ON e.id = re.id_evento AND re.id_usuario = ?
                       WHERE re.id_usuario IS NULL");
$stmt->execute([$usuario_id]);
$eventos_disponibles = $stmt->fetchAll();

// Consultar eventos a los que el usuario está registrado
$stmt = $pdo->prepare("SELECT e.* FROM eventos e 
                       JOIN registro_eventos re ON e.id = re.id_evento 
                       WHERE re.id_usuario = ?");
$stmt->execute([$usuario_id]);
$eventos_registrados = $stmt->fetchAll();

// Obtener detalles de un evento específico
$evento_detalle = null;
$usuarios_evento = [];
if (isset($_GET['id'])) {
    $evento_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->execute([$evento_id]);
    $evento_detalle = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT u.* FROM usuarios u 
                           JOIN registro_eventos re ON u.id = re.id_usuario 
                           WHERE re.id_evento = ?");
    $stmt->execute([$evento_id]);
    $usuarios_evento = $stmt->fetchAll();
}

// Registrar al usuario en un evento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['evento_id'])) {
    $evento_id = $_POST['evento_id'];
    
    // Consultar capacidad del evento y otros detalles
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE id = ?");
    $stmt->execute([$evento_id]);
    $evento = $stmt->fetch();

    if ($evento['capacidad'] > 0) {
        // Registrar al usuario en el evento
        $stmt = $pdo->prepare("INSERT INTO registro_eventos (id_usuario, id_evento) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $evento_id]);

        // Actualizar la capacidad del evento
        $nueva_capacidad = $evento['capacidad'] - 1;
        $stmt = $pdo->prepare("UPDATE eventos SET capacidad = ? WHERE id = ?");
        $stmt->execute([$nueva_capacidad, $evento_id]);

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

        enviarCorreo($correo_usuario, $asunto, $cuerpo); // Llamar a la función para enviar el correo

        header('Location: lista_eventos.php');
        exit();
    } else {
        echo "<script>alert('No hay capacidad disponible para registrarse en este evento.');</script>";
    }
}

function enviarCorreo($destinatario, $asunto, $cuerpo) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'suarezmuchairo@gmail.com';
        $mail->Password = 'xsxuemxevdpymcgw'; // Aquí va la contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('suarezmuchairo@gmail.com', 'Salon de Eventos Carola');
        $mail->addAddress($destinatario);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;
        $mail->isHTML(true);

        $mail->send();
        return 'Correo enviado correctamente';
    } catch (Exception $e) {
        return 'Error al enviar el correo: ' . $mail->ErrorInfo;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 p-4 text-white">
        <nav class="flex justify-between items-center">
            <div>
                <a href="index.php" class="text-lg font-semibold hover:text-gray-300">Inicio</a>
            </div>
            <div>
                <span class="mr-4">Hola, <?php echo htmlspecialchars($usuario_actual['nombre']); ?></span>
                <a href="perfil.php" class="mr-4 hover:text-gray-300">Perfil</a>
                <a href="cerrar_sesion.php" class="hover:text-gray-300">Cerrar Sesión</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Eventos Disponibles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($eventos_disponibles as $evento): ?>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($evento['nombre']); ?></h3>
                    <p class="mb-2"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                    <p class="mb-2"><?php echo htmlspecialchars($evento['fecha']); ?> a las <?php echo htmlspecialchars($evento['hora']); ?></p>
                    <p class="mb-2">En <?php echo htmlspecialchars($evento['lugar']); ?></p>
                    <p class="mb-4">Capacidad: <?php echo htmlspecialchars($evento['capacidad']); ?></p>
                    <form method="POST" action="lista_eventos.php">
                        <input type="hidden" name="evento_id" value="<?php echo $evento['id']; ?>">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Registrarse</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="text-2xl font-semibold mt-8 mb-4">Mis Eventos Registrados</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($eventos_registrados as $evento): ?>
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($evento['nombre']); ?></h3>
                    <p class="mb-2"><?php echo htmlspecialchars($evento['descripcion']); ?></p>
                    <p class="mb-2"><?php echo htmlspecialchars($evento['fecha']); ?> a las <?php echo htmlspecialchars($evento['hora']); ?></p>
                    <p class="mb-2">En <?php echo htmlspecialchars($evento['lugar']); ?></p>
                    <p class="mb-4">Capacidad: <?php echo htmlspecialchars($evento['capacidad']); ?></p>
                    <a href="lista_eventos.php?id=<?php echo $evento['id']; ?>" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Ver Detalles</a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($evento_detalle): ?>
            <h2 class="text-2xl font-semibold mt-8 mb-4">Detalles del Evento: <?php echo htmlspecialchars($evento_detalle['nombre']); ?></h2>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($evento_detalle['nombre']); ?></h3>
                <p class="mb-2"><?php echo htmlspecialchars($evento_detalle['descripcion']); ?></p>
                <p class="mb-2"><?php echo htmlspecialchars($evento_detalle['fecha']); ?> a las <?php echo htmlspecialchars($evento_detalle['hora']); ?></p>
                <p class="mb-2">En <?php echo htmlspecialchars($evento_detalle['lugar']); ?></p>
                <p class="mb-4">Capacidad: <?php echo htmlspecialchars($evento_detalle['capacidad']); ?></p>
                <h4 class="text-lg font-semibold mt-4 mb-2">Usuarios Registrados:</h4>
                <ul class="list-disc list-inside">
                    <?php foreach ($usuarios_evento as $usuario): ?>
                        <li><?php echo htmlspecialchars($usuario['nombre']); ?> (<?php echo htmlspecialchars($usuario['correo']); ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
