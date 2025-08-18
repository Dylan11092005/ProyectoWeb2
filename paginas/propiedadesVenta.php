<?php
require_once '../conexion.php';

// Consulta para todas las propiedades en alquiler
$alquiler = $conexion->query(
    "SELECT * FROM propiedades WHERE tipo='alquiler' ORDER BY idPropiedad DESC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propiedades en Alquiler</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
</head>
<body>
    <header class="encabezadoPrincipal">
        <div class="logoEmpresa">
            <img src="../assets/logo.png" alt="Logo" height="60">
            <div class="textoLogo">
                <span>UTN SOLUTIONS</span><br>
                <span>REAL STATE</span>
            </div>
        </div>
        <nav class="navegacionPrincipal">
            <a href="../index.php">Inicio</a> |
            <a href="#alquiler">Alquiler</a>
        </nav>
    </header>
    <section class="propiedadesAlquiler">
        <h2>Propiedades en Alquiler</h2>
        <div class="listaPropiedades">
            <?php if ($alquiler && $alquiler->num_rows > 0): ?>
                <?php while ($prop = $alquiler->fetch_assoc()): ?>
                    <div class="tarjetaPropiedad">
                        <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                        <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay datos disponibles.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>