<?php
include '../conexion.php';

$sql = "SELECT * FROM configuracion_pagina LIMIT 1";
$result = $conexion->query($sql);
$config = $result->fetch_assoc();

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['colorAzul'])) $errores[] = 'El color es obligatorio.';
    if (empty($_POST['colorAmarillo'])) $errores[] = 'El color es obligatorio.';
    if (empty($_POST['colorGris'])) $errores[] = 'El color es obligatorio.';
    if (empty($_POST['colorBlanco'])) $errores[] = 'El color es obligatorio.';
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido.';
    if (empty($_POST['bannerMensaje'])) $errores[] = 'El mensaje del banner es obligatorio.';
    if (empty($_POST['quienesSomos'])) $errores[] = 'El texto de "Quiénes Somos" es obligatorio.';
    if (empty($_POST['facebook'])) $errores[] = 'El enlace de Facebook es obligatorio.';
    if (empty($_POST['instagram'])) $errores[] = 'El enlace de Instagram es obligatorio.';
    if (empty($_POST['youtube'])) $errores[] = 'El enlace de YouTube es obligatorio.';
    if (empty($_POST['direccion'])) $errores[] = 'La dirección es obligatoria.';
    if (empty($_POST['telefono'])) $errores[] = 'El teléfono es obligatorio.';
    if (empty($errores)) {
        $colorAzul = $_POST['colorAzul'];
        $colorAmarillo = $_POST['colorAmarillo'];
        $colorGris = $_POST['colorGris'];
        $colorBlanco = $_POST['colorBlanco'];
        $iconoPrincipal = $_FILES['iconoPrincipal']['name'] ? $_FILES['iconoPrincipal']['name'] : $config['iconoPrincipal'];
        $iconoBlanco = $_FILES['iconoBlanco']['name'] ? $_FILES['iconoBlanco']['name'] : $config['iconoBlanco'];
        $bannerImagen = $_FILES['bannerImagen']['name'] ? $_FILES['bannerImagen']['name'] : $config['bannerImagen'];
        $bannerMensaje = $_POST['bannerMensaje'];
        $quienesSomos = $_POST['quienesSomos'];
        $quienesSomosImagen = $_FILES['quienesSomosImagen']['name'] ? $_FILES['quienesSomosImagen']['name'] : $config['quienesSomosImagen'];
        $facebook = $_POST['facebook'];
        $instagram = $_POST['instagram'];
        $youtube = $_POST['youtube'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        $carpeta_destino = '../uploads/';
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0777, true);
        }

        foreach ([
            'iconoPrincipal', 'iconoBlanco', 'bannerImagen', 'quienesSomosImagen'
        ] as $imgField) {
            if (isset($_FILES[$imgField]) && $_FILES[$imgField]['error'] == UPLOAD_ERR_OK) {
                $nombre_archivo = uniqid() . '_' . basename($_FILES[$imgField]['name']);
                $ruta_archivo = $carpeta_destino . $nombre_archivo;
                if (move_uploaded_file($_FILES[$imgField]['tmp_name'], $ruta_archivo)) {
                    $$imgField = $nombre_archivo;
                }
            }
        }

        $stmt = $conexion->prepare("UPDATE configuracion_pagina SET colorAzul=?, colorAmarillo=?, colorGris=?, colorBlanco=?, iconoPrincipal=?, iconoBlanco=?, bannerImagen=?, bannerMensaje=?, quienesSomos=?, quienesSomosImagen=?, facebook=?, instagram=?, youtube=?, direccion=?, telefono=?, email=?");
        $stmt->bind_param(
            'ssssssssssssssss',
            $colorAzul,
            $colorAmarillo,
            $colorGris,
            $colorBlanco,
            $iconoPrincipal,
            $iconoBlanco,
            $bannerImagen,
            $bannerMensaje,
            $quienesSomos,
            $quienesSomosImagen,
            $facebook,
            $instagram,
            $youtube,
            $direccion,
            $telefono,
            $email
        );
        $stmt->execute();
        $stmt->close();
        header('Location: personalizar.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Personalizar Página</title>
        <link rel="stylesheet" href="../estilos/estiloAdministrar.css">
        <link rel="stylesheet" href="../estilos/estilosUsuarios.css">
        <link rel="stylesheet" href="../estilos/estiloPersonalizar.css">
</head>

<body>
    <?php require_once '../componentes/botonRegresar.php'; ?>
    <?php if (function_exists('mostrarBotonRegresar')) mostrarBotonRegresar('administrar.php'); ?>
    <div class="personalizar-card">
        <h1 style="text-align:center;">Editar Configuración de la Página</h1>
        <?php if (!empty($errores)): ?>
            <div style="background:#ffc107;color:#18184d;padding:10px;margin-bottom:15px;border-radius:8px;font-weight:bold;">
                <?php foreach ($errores as $err): ?>
                    <div><?php echo $err; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="personalizar-form">
        <label>Color Azul:</label>
        <input type="color" name="colorAzul" value="<?php echo $config['colorAzul']; ?>"><br>
        <label>Color Amarillo:</label>
        <input type="color" name="colorAmarillo" value="<?php echo $config['colorAmarillo']; ?>"><br>
        <label>Color Gris:</label>
        <input type="color" name="colorGris" value="<?php echo $config['colorGris']; ?>"><br>
        <label>Color Blanco:</label>
        <input type="color" name="colorBlanco" value="<?php echo $config['colorBlanco']; ?>"><br>
        <label>Icono Principal:</label>
        <input type="file" name="iconoPrincipal" accept="image/*">
    <?php if ($config['iconoPrincipal']) echo '<img src="../uploads/' . $config['iconoPrincipal'] . '" height="40">'; ?><br>
        <label>Icono Blanco:</label>
        <input type="file" name="iconoBlanco" accept="image/*">
    <?php if ($config['iconoBlanco']) echo '<img src="../uploads/' . $config['iconoBlanco'] . '" height="40">'; ?><br>
        <label>Banner Imagen:</label>
        <input type="file" name="bannerImagen" accept="image/*">
    <?php if ($config['bannerImagen']) echo '<img src="../uploads/' . $config['bannerImagen'] . '" height="40">'; ?><br>
        <label>Banner Mensaje:</label>
        <input type="text" name="bannerMensaje" value="<?php echo htmlspecialchars($config['bannerMensaje']); ?>"><br>
        <label>Quienes Somos:</label>
        <textarea name="quienesSomos"><?php echo htmlspecialchars($config['quienesSomos']); ?></textarea><br>
        <label>Quienes Somos Imagen:</label>
        <input type="file" name="quienesSomosImagen" accept="image/*">
        <?php if ($config['quienesSomosImagen']) echo '<img src="../uploads/' . $config['quienesSomosImagen'] . '" height="40">'; ?><br>
        <label>Facebook:</label>
        <input type="text" name="facebook" value="<?php echo htmlspecialchars($config['facebook']); ?>"><br>
        <label>Instagram:</label>
        <input type="text" name="instagram" value="<?php echo htmlspecialchars($config['instagram']); ?>"><br>
        <label>YouTube:</label>
        <input type="text" name="youtube" value="<?php echo htmlspecialchars($config['youtube']); ?>"><br>
        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?php echo htmlspecialchars($config['direccion']); ?>"><br>
        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($config['telefono']); ?>"><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($config['email']); ?>"><br>
        <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </form>
</body>

</html>