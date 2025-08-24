<?php
require_once '../conexion.php';


$stmtConfig = $conexion->prepare("SELECT * FROM configuracion_pagina LIMIT 1");
$stmtConfig->execute();
$config = $stmtConfig->get_result()->fetch_assoc();
$stmtConfig->close();

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

<body class="body-propiedades">
    <header class="encabezadoPrincipal">
        <div class="logoEmpresa">
           
        </div>
        <nav class="navegacionPrincipal">
            <a href="../index.php">Inicio</a> |
            <a href="#alquiler">Alquiler</a> |
            <a href="propiedadesVenta.php">Venta</a>
            <a href="propiedadesDestacadas.php">Destacadas</a>
        </nav>
    </header>
    <section class="propiedadesAlquiler">
        <h2>Propiedades en Alquiler</h2>
        <div class="listaPropiedades">
            <?php if ($alquiler && $alquiler->num_rows > 0): ?>
                <?php while ($prop = $alquiler->fetch_assoc()): ?>
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