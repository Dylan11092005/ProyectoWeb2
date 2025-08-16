<?php
function mostrarBotonRegresar($paginaDestino = 'index.php') {
    echo '<style>
    .botonRegresar {
        position: absolute;
        top: 24px;
        left: 60px;
        display: inline-block;
        padding: 10px 24px;
        background: #fff;
        color: #2193b0;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(33, 147, 176, 0.10);
        text-decoration: none;
        transition: background 0.2s;
        z-index: 1000;
    }
    .botonRegresar:hover {
        background: linear-gradient(90deg, #6dd5ed 0%, #2193b0 100%);
    }
    </style>';
    echo '<a href="' . htmlspecialchars($paginaDestino) . '" class="botonRegresar">Regresar</a>';
}
?>
