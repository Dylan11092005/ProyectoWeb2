<?php
include_once '../conexion.php';


function eliminarPorId($conexion, $tabla, $columnaId, $id) {
    $tipo = is_int($id) ? 'i' : 's';
    $sql = "DELETE FROM `$tabla` WHERE `$columnaId` = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param($tipo, $id);
    $resultado = $stmt->execute();
    return $resultado;
}
?>
