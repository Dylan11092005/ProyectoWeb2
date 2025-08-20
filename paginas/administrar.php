<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="../estilos/estiloLogin.css">
    <link rel="stylesheet" href="../estilos/estiloAdministrar.css">
    <style>
    .grid.centered {
        justify-content: center;
        display: flex;
        gap: 32px;
    }
    </style>
</head>
<body>
    <h1 class="titulo">Panel Principal</h1>
    <?php $privilegio = $_SESSION['privilegio'] ?? ''; ?>
    <?php $privilegio = $_SESSION['privilegio'] ?? ''; ?>
    <section class="grid<?php echo ($privilegio === 'agente') ? ' centered' : ' cols-3'; ?>">
        <div class="card">
            <img src="../img/usuarios.png" alt="Usuarios">
            <h3>Usuarios</h3>
            <p>Gestionar usuarios del sistema</p>
            <a class="btn" href="administrarUsuarios.php">Ir</a>
        </div>
        <?php if ($privilegio !== 'agente'): ?>
        <div class="card">
            <img src="../img/paleta.png" alt="Personalizar">
            <h3>Personalizar</h3>
            <p>Configurar preferencias de diseño</p>
            <a class="btn" href="personalizar.php">Ir</a>
        </div>
        <?php endif; ?>
        <div class="card">
            <img src="../img/propiedades.png" alt="Propiedades">
            <h3>Propiedades</h3>
            <p>Gestionar propiedades del sistema</p>
            <a class="btn" href="administrarPropiedades.php">Ir</a>
        </div>
    </section>
    <form action="../componentes/cerrarSesion.php" method="post" style="margin-top:20px; text-align:center;">
        <button type="submit" class="btn">Cerrar sesión</button>
    </form>
</body>
</html>
