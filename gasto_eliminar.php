<?php
// US2 · Eliminar un gasto (pide confirmación antes de borrar).
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'gastos';
$db = conexion();
$id = (int) ($_GET['id'] ?? 0);

$consulta = $db->prepare('SELECT concepto, monto, fecha FROM Gastos WHERE id_gasto = ?');
$consulta->execute([$id]);
$gasto = $consulta->fetch();

if (!$gasto) {
    redirigir('gastos.php', 'El gasto ya no existe.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $db->prepare('DELETE FROM Gastos WHERE id_gasto = ?');
    $sql->execute([$id]);
    redirigir('gastos.php', 'Gasto eliminado correctamente.');
}

require 'includes/header.php';
?>
<h1>Eliminar gasto</h1>

<div class="confirmacion">
    <strong>¿Seguro que deseas eliminar este gasto?</strong>
    <table>
        <tr><th>Concepto</th><td><?= e($gasto['concepto']) ?></td></tr>
        <tr><th>Monto</th><td><?= dinero($gasto['monto']) ?></td></tr>
        <tr><th>Fecha</th><td><?= date('d/m/Y', strtotime($gasto['fecha'])) ?></td></tr>
    </table>
    <form method="post">
        <button type="submit" class="boton boton-chico">SÍ, ELIMINAR</button>
        <a class="boton boton-chico" href="gastos.php">CANCELAR</a>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
