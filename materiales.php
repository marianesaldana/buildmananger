<?php
// US3 · Lista de materiales con buscador por nombre y los botones Nuevo, Editar y Eliminar.
require 'config/db.php';

$pagina = 'materiales';
$materiales = [];
$error = null;
$buscar = trim($_GET['buscar'] ?? '');

try {
    $db = conexion();
    if ($buscar !== '') {
        $consulta = $db->prepare('SELECT id_material, nombre, unidad, cantidad, costo_unitario
                                  FROM Materiales WHERE nombre ILIKE ? ORDER BY nombre');
        $consulta->execute(['%' . $buscar . '%']);
        $materiales = $consulta->fetchAll();
    } else {
        $materiales = $db
            ->query('SELECT id_material, nombre, unidad, cantidad, costo_unitario FROM Materiales ORDER BY nombre')
            ->fetchAll();
    }
} catch (PDOException $ex) {
    $error = 'No se pudo cargar la lista de materiales.';
}

require 'includes/header.php';
?>
<h1>Materiales</h1>

<?php mostrar_mensaje(); ?>

<form class="buscador" method="get">
    <input type="text" name="buscar" placeholder="Buscar material por nombre" value="<?= e($buscar) ?>">
    <button type="submit" class="boton boton-chico">BUSCAR</button>
    <?php if ($buscar !== ''): ?>
        <a class="boton boton-chico" href="materiales.php">VER TODOS</a>
    <?php endif; ?>
</form>

<div class="acciones-superiores">
    <a class="boton boton-chico" href="material_form.php">NUEVO MATERIAL</a>
</div>

<?php if ($error): ?>
    <p class="descripcion"><?= e($error) ?></p>
<?php elseif (!$materiales && $buscar !== ''): ?>
    <p class="descripcion">No se encontró ningún material con el nombre "<?= e($buscar) ?>".</p>
<?php elseif (!$materiales): ?>
    <p class="descripcion">No hay materiales registrados.</p>
<?php else: ?>
    <?php if ($buscar !== ''): ?>
        <p class="descripcion">Resultados para "<?= e($buscar) ?>": <?= count($materiales) ?></p>
    <?php endif; ?>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>Costo unitario</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($materiales as $m): ?>
        <tr>
            <td><?= e($m['nombre']) ?></td>
            <td><?= e($m['unidad']) ?></td>
            <td class="num"><?= rtrim(rtrim(number_format((float) $m['cantidad'], 2), '0'), '.') ?></td>
            <td class="num"><?= dinero($m['costo_unitario']) ?></td>
            <td>
                <a href="material_form.php?id=<?= (int) $m['id_material'] ?>">Editar</a>
                <a href="material_eliminar.php?id=<?= (int) $m['id_material'] ?>">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
