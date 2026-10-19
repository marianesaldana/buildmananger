<?php
// US1 · Eliminar un empleado (pide confirmación antes de borrar).
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'empleados';
$db = conexion();
$rfc = trim($_GET['rfc'] ?? '');

$consulta = $db->prepare('SELECT Nombre, RFC, Puesto, Salario FROM Empleados WHERE RFC = ?');
$consulta->execute([$rfc]);
$empleado = $consulta->fetch();

if (!$empleado) {
    redirigir('empleados.php', 'El empleado ya no existe.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = $db->prepare('DELETE FROM Empleados WHERE RFC = ?');
    $sql->execute([$rfc]);
    redirigir('empleados.php', 'Empleado eliminado correctamente.');
}

require 'includes/header.php';
?>
<h1>Eliminar empleado</h1>

<div class="confirmacion">
    <strong>¿Seguro que deseas eliminar a este empleado?</strong>
    <table>
        <tr><th>Nombre</th><td><?= e($empleado['nombre']) ?></td></tr>
        <tr><th>RFC</th><td><?= e(trim($empleado['rfc'])) ?></td></tr>
        <tr><th>Puesto</th><td><?= e($empleado['puesto']) ?></td></tr>
        <tr><th>Salario</th><td><?= dinero($empleado['salario']) ?></td></tr>
    </table>
    <form method="post">
        <button type="submit" class="boton boton-chico">SÍ, ELIMINAR</button>
        <a class="boton boton-chico" href="empleados.php">CANCELAR</a>
    </form>
</div>

<?php require 'includes/footer.php'; ?>
