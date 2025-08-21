<?php
require_once 'conexion.php';

// Configuración de la página (no hay entrada de usuario, pero se puede usar consulta preparada)
$stmtConfig = $conexion->prepare("SELECT * FROM configuracion_pagina LIMIT 1");
$stmtConfig->execute();
$config = $stmtConfig->get_result()->fetch_assoc();
$stmtConfig->close();

// Propiedades destacadas (3 últimas)
$stmtDestacadas = $conexion->prepare("SELECT * FROM propiedades WHERE destacada=? ORDER BY idPropiedad DESC LIMIT 3");
$destacada = 1;
$stmtDestacadas->bind_param("i", $destacada);
$stmtDestacadas->execute();
$destacadas = $stmtDestacadas->get_result();
$stmtDestacadas->close();

// Propiedades en venta (3 últimas)
$tipoVenta = 'venta';
$stmtVenta = $conexion->prepare("SELECT * FROM propiedades WHERE tipo=? ORDER BY idPropiedad DESC LIMIT 3");
$stmtVenta->bind_param("s", $tipoVenta);
$stmtVenta->execute();
$venta = $stmtVenta->get_result();
$stmtVenta->close();

// Propiedades en alquiler (3 últimas)
$tipoAlquiler = 'alquiler';
$stmtAlquiler = $conexion->prepare("SELECT * FROM propiedades WHERE tipo=? ORDER BY idPropiedad DESC LIMIT 3");
$stmtAlquiler->bind_param("s", $tipoAlquiler);
$stmtAlquiler->execute();
$alquiler = $stmtAlquiler->get_result();
$stmtAlquiler->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>UTN Solutions Real Estate</title>
    <style>
        :root {
            --colorAzul:
                <?php echo isset($config['colorAzul']) ? $config['colorAzul'] : '#18184d'; ?>
            ;
            --colorAmarillo:
                <?php echo isset($config['colorAmarillo']) ? $config['colorAmarillo'] : '#ffc107'; ?>
            ;
            --colorGris:
                <?php echo isset($config['colorGris']) ? $config['colorGris'] : '#f5f5f5'; ?>
            ;
            --colorBlacono:
                <?php echo isset($config['colorBlanco']) ? $config['colorBlanco'] : '#ffffff'; ?>
            ;
            --color-header: #0a0a1a;
        }
    </style>
    <link rel="stylesheet" href="estilos/estilos.css">
    <link rel="stylesheet" href="./estilos/estiloBusqueda.css">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <div class="barraLogin">
        <a href="paginas/login.php" class="iconoLogin" title="Iniciar sesión">
            <img src="img/usuarios.png" alt="Login" height="32">
        </a>
    </div>

    <header class="encabezadoPrincipal">
        <div class="logoRedesContainer" style="display: flex; flex-direction: column; align-items: center;">
            <div class="logoEmpresa">
                <img src="uploads/<?php echo htmlspecialchars($config['iconoPrincipal']); ?>"
                    alt="UTN Solutions Real Estate" height="80">
            </div>
            <div class="redesFooter" style="display: flex; flex-direction: row; gap: 16px; margin-top: 16px;">
                <?php if ($config): ?>
                    <a href="<?php echo htmlspecialchars($config['facebook']); ?>" target="_blank" id="facebookFooter">
                        <img src="img/facebook.png" alt="Facebook" height="40">
                    </a>
                    <a href="<?php echo htmlspecialchars($config['youtube']); ?>" target="_blank" id="youtubeFooter">
                        <img src="img/youtube.png" alt="YouTube" height="40">
                    </a>
                    <a href="<?php echo htmlspecialchars($config['instagram']); ?>" target="_blank" id="instagramFooter">
                        <img src="img/instagram.png" alt="Instagram" height="40">
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="navegacionPrincipal">
            <a href="index.php">Inicio</a> |
            <a href="#quienes">Quiénes Somos</a> |
            <a href="#alquiler">Alquileres</a> |
            <a href="#venta">Ventas</a> |
            <a href="#contacto">Contáctenos</a>
        </nav>
    </header>

    <div class="barraBusqueda">
        <form action="componentes/buscar.php" method="GET" class="formularioBusqueda">
            <input type="text" name="q" placeholder="Buscar propiedades...">
            <button type="submit">🔍</button>
        </form>
    </div>

    <!-- Banner principal -->
    <section class="bannerPrincipal">
        <?php if ($config): ?>
            <img src="uploads/<?php echo htmlspecialchars($config['bannerImagen']); ?>" alt="Banner principal"
                class="imagenBanner" id="imagenBanner">
            <div class="superposicionBanner">
                <h1 id="mensajeBanner"><?php echo htmlspecialchars($config['bannerMensaje']); ?></h1>
            </div>
        <?php else: ?>
            <div class="superposicionBanner">
                <h1 id="mensajeBanner">No hay datos disponibles.</h1>
            </div>
        <?php endif; ?>
    </section>

    <!-- Sección Quienes Somos -->
    <section class="quienesSomos" id="quienes">
        <div class="textoQuienes">
            <h2>Quiénes Somos</h2>
            <p id="descripcionQuienes">
                <?php if ($config): ?>
                    <?php echo nl2br(htmlspecialchars($config['quienesSomos'])); ?>
                <?php else: ?>
                    No hay datos disponibles.
                <?php endif; ?>
            </p>
        </div>
        <div class="imagenQuienes">
            <?php if ($config): ?>

                <img src="uploads/<?php echo htmlspecialchars($config['quienesSomosImagen']); ?>" alt="Equipo UTN Solutions"
                    height="180" id="imagenQuienes">
            <?php endif; ?>
        </div>
    </section>


    <!-- Propiedades Destacadas -->
    <section class="propiedadesDestacadas" id="destacadas">
        <h2>Propiedades Destacadas</h2>
        <div class="listaPropiedades">
            <?php if ($destacadas && $destacadas->num_rows > 0): ?>
                <?php while ($prop = $destacadas->fetch_assoc()): ?>
                    <a href="paginas/propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="tarjetaPropiedad">
                        <img src="uploads/<?php echo htmlspecialchars($prop['imagen_destacada']); ?>"
                            alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                        <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay datos disponibles.</p>
            <?php endif; ?>
        </div>
        <div class="verMasSeccion">
            <a href="paginas/propiedadesDestacadas.php" class="botonVerMas">Ver más</a>
        </div>
    </section>

    <!-- Propiedades en Venta -->
    <section class="propiedadesVenta" id="venta">
        <h2>Propiedades en Venta</h2>
        <div class="listaPropiedades">
            <?php if ($venta && $venta->num_rows > 0): ?>
                <?php while ($prop = $venta->fetch_assoc()): ?>
                    <a href="paginas/propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="tarjetaPropiedad">
                        <img src="uploads/<?php echo htmlspecialchars($prop['imagen_destacada']); ?>"
                            alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                        <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay datos disponibles.</p>
            <?php endif; ?>
        </div>
        <div class="verMasSeccion">
            <a href="paginas/propiedadesVenta.php" class="botonVerMas">Ver más</a>
        </div>
    </section>

    <!-- Propiedades en Alquiler -->
    <section class="propiedadesAlquiler" id="alquiler">
        <h2>Propiedades en Alquiler</h2>
        <div class="listaPropiedades">
            <?php if ($alquiler && $alquiler->num_rows > 0): ?>
                <?php while ($prop = $alquiler->fetch_assoc()): ?>
                    <a href="paginas/propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="tarjetaPropiedad">
                        <img src="uploads/<?php echo htmlspecialchars($prop['imagen_destacada']); ?>"
                            alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                        <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                        <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay datos disponibles.</p>
            <?php endif; ?>
        </div>
        <div class="verMasSeccion">
            <a href="paginas/propiedadesAlquiler.php" class="botonVerMas">Ver más</a>
        </div>
    </section>

    <!-- Footer con contacto -->
    <footer>
        <div class="infoFooter">
            <div class="datosFooter">
                <div>
                    <img src="img/direccion.png" alt="Logo UTN" height="28" class="logoMiniFooter">
                    <strong><em>Dirección:</em></strong>
                    <span
                        id="direccionFooter"><?php echo $config ? htmlspecialchars($config['direccion']) : 'No hay datos disponibles.'; ?></span>
                </div>
                <div>
                    <img src="img/telefono.png" alt="Logo UTN" height="28" class="logoMiniFooter">
                    <strong><em>Teléfono:</em></strong>
                    <span
                        id="telefonoFooter"><?php echo $config ? htmlspecialchars($config['telefono']) : 'No hay datos disponibles.'; ?></span>
                </div>
                <div>
                    <img src="img/email.png" alt="Logo UTN" height="28" class="logoMiniFooter">
                    <strong><em>Email:</em></strong>
                    <span
                        id="emailFooter"><?php echo $config ? htmlspecialchars($config['email']) : 'No hay datos disponibles.'; ?></span>
                </div>
            </div>
            <div class="logoFooterRedesContainer">
                <div class="logoFooter">
                    <?php if ($config): ?>
                        <img src="uploads/<?php echo htmlspecialchars($config['iconoBlanco']); ?>"
                            alt="UTN Solutions Real Estate" height="80" id="logoFooter">
                    <?php endif; ?>
                </div>
                <div class="redesFooter">
                    <?php if ($config): ?>
                        <a href="<?php echo htmlspecialchars($config['facebook']); ?>" target="_blank"
                            id="facebookFooter"><img src="img/facebook.png" alt="Facebook" height="32"></a>
                        <a href="<?php echo htmlspecialchars($config['youtube']); ?>" target="_blank"
                            id="youtubeFooter"><img src="img/youtube.png" alt="YouTube" height="32"></a>
                        <a href="<?php echo htmlspecialchars($config['instagram']); ?>" target="_blank"
                            id="instagramFooter"><img src="img/instagram.png" alt="Instagram" height="32"></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="contactoFooter" id="contacto">
                <div class="mensajeContacto"></div>
                <form action="paginas/contacto.php" method="POST" class="formContactoGrid">
                    <h4>Contáctanos</h4>
                    <div class="formRow">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="formRow">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="formRow">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" required>
                    </div>
                    <div class="formRow">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" required></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </footer>
    <div class="copyFooter">
        Derechos Reservados 2025
    </div>
</body>

</html>