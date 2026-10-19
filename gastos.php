<?php
// US2 · Lista de gastos con el total al final y los botones Nuevo, Editar y Eliminar.
require 'config/db.php';

$pagina = 'gastos';
$gastos = [];
$total = 0;
$error = null;

try {
    $gastos = conexion()
        ->query('SELECT id_gasto, concepto, monto, fecha FROM Gastos ORDER BY fecha, id_gasto')
        ->fetchAll();
    foreach ($gastos as $g) {
        $total += (float) $g['monto'];
    }
} catch (PDOException $ex) {
    $error = 'No se pudo cargar la lista de gastos.';
}

require 'includes/header.php';
?>
<h1>Gastos</h1>

<?php mostrar_mensaje(); ?>

<div class="acciones-superiores">
    <a class="boton boton-chico" href="gasto_form.php">NUEVO GASTO</a>
</div>

<?php if ($error): ?>
    <p class="descripcion"><?= e($error) ?></p>
<?php elseif (!$gastos): ?>
    <p class="descripcion">No hay gastos registrados.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Concepto</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($gastos as $g): ?>
        <tr>
            <td><?= e($g['concepto']) ?></td>
            <td class="num"><?= dinero($g['monto']) ?></td>
            <td><?= date('d/m/Y', strtotime($g['fecha'])) ?></td>
            <td>
                <a href="gasto_form.php?id=<?= (int) $g['id_gasto'] ?>">Editar</a>
                <a href="gasto_eliminar.php?id=<?= (int) $g['id_gasto'] ?>">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr class="fila-total">
            <td>TOTAL GASTADO</td>
            <td class="num"><?= dinero($total) ?></td>
            <td colspan="2"></td>
        </tr>
    </table>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
