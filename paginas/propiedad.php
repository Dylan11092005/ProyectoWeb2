<?php
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$prop = null;
$agente = null;

if ($id > 0) {
    $res = $conexion->query("SELECT * FROM propiedades WHERE idPropiedad = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $prop = $res->fetch_assoc();
        // Obtener datos del agente
        $idAgente = intval($prop['idAgente']);
        $resAgente = $conexion->query("SELECT nombre, telefono, email FROM usuarios WHERE idUsuario = $idAgente LIMIT 1");
        if ($resAgente && $resAgente->num_rows > 0) {
            $agente = $resAgente->fetch_assoc();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle de Propiedad</title>
    <link rel="stylesheet" href="../estilos/estiloPropiedad.css">
</head>

<body>
    <?php if ($prop): ?>
        <div class="contenedorPropiedad">
            <h2><?php echo htmlspecialchars($prop['titulo']); ?></h2>
            <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="Imagen de la propiedad"
                style="max-width:400px;">
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($prop['tipo']); ?></p>
            <p><strong>Destacada:</strong> <?php echo $prop['destacada'] ? 'Sí' : 'No'; ?></p>
            <p><strong>Precio:</strong> $<?php echo number_format($prop['precio'], 2); ?></p>
            <p><strong>Descripción breve:</strong> <?php echo nl2br(htmlspecialchars($prop['descripcionBreve'])); ?></p>
            <p><strong>Descripción larga:</strong> <?php echo nl2br(htmlspecialchars($prop['descripcion_larga'])); ?></p>
            <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($prop['ubicacion']); ?></p>
            <?php if (!empty($prop['mapa'])): ?>
                <div class="mapa">
                    <strong>Mapa:</strong>
                    <div><?php echo $prop['mapa']; // Aquí puedes insertar un iframe de Google Maps si guardas el código HTML ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($agente): ?>
                <h3>Agente de Ventas</h3>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($agente['nombre']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($agente['telefono']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($agente['email']); ?></p>
            <?php endif; ?>
            <?php
            require_once '../componentes/botonRegresar.php';
            mostrarBotonRegresar('../index.php');
            ?>
        </div>
    <?php else: ?>
        <div class="contenedorPropiedad">
            <p>Propiedad no encontrada.</p>
            <?php
            require_once '../componentes/botonRegresar.php';
            mostrarBotonRegresar('../index.php');
            ?>
        </div>
    <?php endif; ?>
</body>

</html>