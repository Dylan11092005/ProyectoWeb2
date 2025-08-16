<?php
require_once 'conexion.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$resultados = [];

if ($q !== '') {
    $stmt = $conexion->prepare(
        "SELECT * FROM propiedades WHERE titulo LIKE CONCAT('%', ?, '%') OR descripcionBreve LIKE CONCAT('%', ?, '%') ORDER BY idPropiedad DESC"
    );
    $stmt->bind_param("ss", $q, $q);
    $stmt->execute();
    $resultados = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="barraBusqueda">
        <form action="buscar.php" method="GET" class="formularioBusqueda">
            <input type="text" name="q" placeholder="Buscar propiedades..." value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit">🔍</button>
        </form>
    </div>
    <section class="resultadosBusqueda">
        <h2>Resultados de búsqueda</h2>
        <div class="listaPropiedades" id="resultadosBusqueda">
            <?php if ($q !== ''): ?>
                <?php if ($resultados && $resultados->num_rows > 0): ?>
                    <?php while($prop = $resultados->fetch_assoc()): ?>
                        <div class="tarjetaPropiedad">
                            <img src="<?php echo htmlspecialchars($prop['imagen_destacada']); ?>" alt="<?php echo htmlspecialchars($prop['titulo']); ?>">
                            <h3><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($prop['descripcionBreve']); ?></p>
                            <span class="precioPropiedad">Precio: $<?php echo number_format($prop['precio'], 2); ?></span>
                            <a href="propiedad.php?id=<?php echo $prop['idPropiedad']; ?>" class="botonVerMas">Ver más...</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="mensajeBusqueda">
                        No se encontraron propiedades para tu búsqueda.
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mensajeBusqueda">
                    Ingresa un término para buscar propiedades.
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>