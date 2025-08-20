<?php
require_once '../conexion.php';

// Consulta segura para todas las propiedades destacadas
$stmtDestacadas = $conexion->prepare("SELECT * FROM propiedades WHERE destacada=? ORDER BY idPropiedad DESC");
$destacada = 1;
$stmtDestacadas->bind_param("i", $destacada);
$stmtDestacadas->execute();
$destacadas = $stmtDestacadas->get_result();
$stmtDestacadas->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Propiedades Destacadas</title>
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
            <a href="#destacadas">Destacadas</a>
        </nav>
    </header>
    <section class="propiedadesDestacadas">
        <h2>Propiedades Destacadas</h2>
        <div class="listaPropiedades">
            <?php if ($destacadas && $destacadas->num_rows > 0): ?>
                <?php while ($prop = $destacadas->fetch_assoc()): ?>
                    <div class="tarjetaPropiedad">
                        <img src="../uploads/<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
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