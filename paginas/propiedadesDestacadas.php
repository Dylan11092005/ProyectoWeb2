<?php
require_once '../conexion.php';



$stmtConfig = $conexion->prepare("SELECT * FROM configuracion_pagina LIMIT 1");
$stmtConfig->execute();
$config = $stmtConfig->get_result()->fetch_assoc();
$stmtConfig->close();

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

<body class="body-propiedades">
    <header class="encabezadoPrincipal">
        <div class="logoEmpresa">
            
        </div>
        <nav class="navegacionPrincipal">
            <a href="../index.php">Inicio</a> |
            <a href="#destacadas">Destacadas</a> |
            <a href="propiedadesAlquiler.php">Alquiler</a> |
            <a href="propiedadesVenta.php">Venta</a>
        </nav>
    </header>
    <section class="propiedadesDestacadas">
        <h2>Propiedades Destacadas</h2>
        <div class="listaPropiedades">
            <?php if ($destacadas && $destacadas->num_rows > 0): ?>
                <?php while ($prop = $destacadas->fetch_assoc()): ?>
                    <a href="propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="tarjetaPropiedad">
                        <img src="../uploads/<?php echo htmlspecialchars($prop['imagen_destacada']); ?>"
                            alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                        <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    </a>
                <?php endwhile; ?>
                <?php
            require_once '../componentes/botonRegresar.php';
            mostrarBotonRegresar('../index.php');
            ?>
            <?php else: ?>
                <p>No hay datos disponibles.</p>
                <?php
            require_once '../componentes/botonRegresar.php';
            mostrarBotonRegresar('../index.php');
            ?>
            <?php endif; ?>
        </div>
    </section>
</body>

</html>