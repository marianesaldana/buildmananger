<?php
// US3 · Eliminar un material (pide confirmación antes de borrar).
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'materiales';
$db = conexion();
$id = (int) ($_GET['id'] ?? 0);

$consulta = $db->prepare('SELECT nombre, unidad, cantidad, costo_unitario FROM Materiales WHERE id_material = ?');
$consulta->execute([$id]);
$material = $consulta->fetch();

if (!$material) {
    redirigir('materiales.php', 'El material ya no existe.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $db->prepare('DELETE FROM Materiales WHERE id_material = ?');
    $sql->execute([$id]);
    redirigir('materiales.php', 'Material eliminado correctamente.');
}

require 'includes/header.php';
?>
<h1>Eliminar material</h1>

<div class="confirmacion">
    <strong>¿Seguro que deseas eliminar este material?</strong>
    <table>
        <tr><th>Nombre</th><td><?= e($material['nombre']) ?></td></tr>
        <tr><th>Unidad</th><td><?= e($material['unidad']) ?></td></tr>
        <tr><th>Cantidad</th><td><?= e((float) $material['cantidad']) ?></td></tr>
        <tr><th>Costo unitario</th><td><?= dinero($material['costo_unitario']) ?></td></tr>
    </table>
    <form method="post">
        <button type="submit" class="boton boton-chico">SÍ, ELIMINAR</button>
        <a class="boton boton-chico" href="materiales.php">CANCELAR</a>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
