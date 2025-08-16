<?php
require_once 'conexion.php';

// Configuración de la página
$config = $conexion->query("SELECT * FROM configuracion_pagina LIMIT 1")->fetch_assoc();

// Propiedades destacadas (3 últimas)
$destacadas = $conexion->query(
    "SELECT * FROM propiedades WHERE destacada=1 ORDER BY idPropiedad DESC LIMIT 3"
);

// Propiedades en venta (3 últimas)
$venta = $conexion->query(
    "SELECT * FROM propiedades WHERE tipo='venta' ORDER BY idPropiedad DESC LIMIT 3"
);

// Propiedades en alquiler (3 últimas)
$alquiler = $conexion->query(
    "SELECT * FROM propiedades WHERE tipo='alquiler' ORDER BY idPropiedad DESC LIMIT 3"
);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UTN Solutions Real Estate</title>
    <link rel="stylesheet" href="./estilos/estilos.css">
</head>
<body>
    <div class="barraLogin">
        <a href="paginas/login.php" class="iconoLogin" title="Iniciar sesión">
            <img src="assets/user-icon.png" alt="Login" height="32">
        </a>
    </div>

    <header class="encabezadoPrincipal">
        <div class="logoEmpresa">
            <img src="<?php echo htmlspecialchars($config['iconoPrincipal']); ?>" alt="UTN Solutions Real Estate" height="60">
            <div class="textoLogo">
                <span>UTN SOLUTIONS</span><br>
                <span>REAL STATE</span>
            </div>
        </div>
        <nav class="navegacionPrincipal">
            <a href="index.php">Inicio</a> |
            <a href="#quienes">Quiénes Somos</a> |
            <a href="alquileres.php">Alquileres</a> |
            <a href="ventas.php">Ventas</a> |
            <a href="#contacto">Contáctenos</a>
        </nav>
    </header>

    <div class="barraBusqueda">
        <form action="buscar.php" method="GET" class="formularioBusqueda">
            <input type="text" name="q" placeholder="Buscar propiedades...">
            <button type="submit">🔍</button>
        </form>
    </div>

    <!-- Banner principal -->
    <section class="bannerPrincipal">
        <img src="<?php echo htmlspecialchars($config['bannerImagen']); ?>" alt="Banner principal" class="imagenBanner" id="imagenBanner">
        <div class="superposicionBanner">
            <h1 id="mensajeBanner"><?php echo htmlspecialchars($config['bannerMensaje']); ?></h1>
        </div>
    </section>

    <!-- Sección Quienes Somos -->
    <section class="quienesSomos" id="quienes">
        <div class="textoQuienes">
            <h2>Quiénes Somos</h2>
            <p id="descripcionQuienes">
                <?php echo nl2br(htmlspecialchars($config['quienesSomos'])); ?>
            </p>
        </div>
        <div class="imagenQuienes">
            <img src="<?php echo htmlspecialchars($config['quienesSomosImagen']); ?>" alt="Equipo UTN Solutions" height="180" id="imagenQuienes">
        </div>
    </section>

    <!-- Propiedades Destacadas -->
    <section class="propiedadesDestacadas">
        <h2>Propiedades Destacadas</h2>
        <div class="listaPropiedades" id="destacadas">
            <?php while($prop = $destacadas->fetch_assoc()): ?>
                <div class="tarjetaPropiedad">
                    <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                    <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    <a href="propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="botonVerMas">Ver más...</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Propiedades en Venta -->
    <section class="propiedadesVenta">
        <h2>Propiedades en Venta</h2>
        <div class="listaPropiedades" id="venta">
            <?php while($prop = $venta->fetch_assoc()): ?>
                <div class="tarjetaPropiedad">
                    <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                    <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    <a href="propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="botonVerMas">Ver más...</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Propiedades en Alquiler -->
    <section class="propiedadesAlquiler">
        <h2>Propiedades en Alquiler</h2>
        <div class="listaPropiedades" id="alquiler">
            <?php while($prop = $alquiler->fetch_assoc()): ?>
                <div class="tarjetaPropiedad">
                    <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                    <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                    <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                    <a href="propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="botonVerMas">Ver más...</a>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer con contacto -->
    <footer>
        <div class="infoFooter">
            <div>
                <strong>Dirección:</strong> <span id="direccionFooter"><?php echo htmlspecialchars($config['direccion']); ?></span><br>
                <strong>Teléfono:</strong> <span id="telefonoFooter"><?php echo htmlspecialchars($config['telefono']); ?></span><br>
                <strong>Email:</strong> <span id="emailFooter"><?php echo htmlspecialchars($config['email']); ?></span>
            </div>
            <div class="logoFooter">
                <img src="<?php echo htmlspecialchars($config['iconoBlanco']); ?>" alt="UTN Solutions Real Estate" height="50" id="logoFooter">
            </div>
            <div class="redesFooter">
                <a href="<?php echo htmlspecialchars($config['facebook']); ?>" target="_blank" id="facebookFooter"><img src="assets/facebook.png" alt="Facebook" height="32"></a>
                <a href="<?php echo htmlspecialchars($config['instagram']); ?>" target="_blank" id="instagramFooter"><img src="assets/instagram.png" alt="Instagram" height="32"></a>
                <a href="<?php echo htmlspecialchars($config['youtube']); ?>" target="_blank" id="youtubeFooter"><img src="assets/youtube.png" alt="YouTube" height="32"></a>
            </div>
        </div>
        <div class="contactoFooter" id="contacto">
            <div class="mensajeContacto">
                <!-- Mensajes dinámicos del backend -->
            </div>
            <form action="backend/contacto.php" method="POST">
                <h4>Contáctanos</h4>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="telefono" placeholder="Teléfono" required>
                <textarea name="mensaje" placeholder="Mensaje" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
        <div class="copyFooter">
            Derechos Reservados 2024
        </div>
    </footer>
</body>
</html>