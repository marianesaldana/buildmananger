<?php
// US1 · Lista de empleados con los botones Nuevo, Editar y Eliminar.
require 'config/db.php';

$pagina = 'empleados';
$empleados = [];
$error = null;

try {
    $empleados = conexion()
        ->query('SELECT Nombre AS nombre, RFC AS rfc, Puesto AS puesto, Salario AS salario
                 FROM Empleados ORDER BY Nombre')
        ->fetchAll();
} catch (PDOException $ex) {
    $error = 'No se pudo cargar la lista de empleados.';
}

require 'includes/header.php';
?>
<h1>Empleados</h1>

<?php mostrar_mensaje(); ?>

<div class="acciones-superiores">
    <a class="boton boton-chico" href="empleado_form.php">NUEVO EMPLEADO</a>
</div>

<?php if ($error): ?>
    <p class="descripcion"><?= e($error) ?></p>
<?php elseif (!$empleados): ?>
    <p class="descripcion">No hay empleados registrados.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Nombre</th>
            <th>RFC</th>
            <th>Puesto</th>
            <th>Salario</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($empleados as $emp): ?>
        <tr>
            <td><?= e($emp['nombre']) ?></td>
            <td><?= e(trim($emp['rfc'])) ?></td>
            <td><?= e($emp['puesto']) ?></td>
            <td class="num"><?= dinero($emp['salario']) ?></td>
            <td>
                <a href="empleado_form.php?rfc=<?= urlencode(trim($emp['rfc'])) ?>">Editar</a>
                <a href="empleado_eliminar.php?rfc=<?= urlencode(trim($emp['rfc'])) ?>">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
