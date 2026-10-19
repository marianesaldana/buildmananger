<?php
// US3 · Formato para registrar (Nuevo) o modificar (Editar) un material.
// Sin ?id= es alta; con ?id=N es edición.
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'materiales';
$db = conexion();

$id = (int) ($_GET['id'] ?? 0);
$editando = $id > 0;
$errores = [];
$datos = ['nombre' => '', 'unidad' => '', 'cantidad' => '', 'costo_unitario' => ''];

if ($editando) {
    $consulta = $db->prepare('SELECT nombre, unidad, cantidad, costo_unitario FROM Materiales WHERE id_material = ?');
    $consulta->execute([$id]);
    $fila = $consulta->fetch();
    if (!$fila) {
        redirigir('materiales.php', 'El material ya no existe.');
    }
    $datos = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos['nombre']         = trim($_POST['nombre'] ?? '');
    $datos['unidad']         = trim($_POST['unidad'] ?? '');
    $datos['cantidad']       = trim($_POST['cantidad'] ?? '');
    $datos['costo_unitario'] = trim($_POST['costo_unitario'] ?? '');

    // Validaciones
    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }
    if ($datos['unidad'] === '') {
        $errores[] = 'La unidad de medida es obligatoria.';
    }
    if ($datos['cantidad'] === '') {
        $errores[] = 'La cantidad es obligatoria.';
    } elseif (!is_numeric($datos['cantidad']) || (float) $datos['cantidad'] < 0) {
        $errores[] = 'La cantidad debe ser un número igual o mayor a 0.';
    }
    if ($datos['costo_unitario'] === '') {
        $errores[] = 'El costo unitario es obligatorio.';
    } elseif (!is_numeric($datos['costo_unitario']) || (float) $datos['costo_unitario'] <= 0) {
        $errores[] = 'El costo unitario debe ser un número mayor a 0.';
    }

    // Guardar
    if (!$errores) {
        if ($editando) {
            $sql = $db->prepare('UPDATE Materiales SET nombre = ?, unidad = ?, cantidad = ?, costo_unitario = ?
                                 WHERE id_material = ?');
            $sql->execute([$datos['nombre'], $datos['unidad'], $datos['cantidad'], $datos['costo_unitario'], $id]);
            redirigir('materiales.php', 'Material actualizado correctamente.');
        } else {
            $sql = $db->prepare('INSERT INTO Materiales (nombre, unidad, cantidad, costo_unitario) VALUES (?, ?, ?, ?)');
            $sql->execute([$datos['nombre'], $datos['unidad'], $datos['cantidad'], $datos['costo_unitario']]);
            redirigir('materiales.php', 'Material registrado correctamente.');
        }
    }
}

require 'includes/header.php';
?>
<h1><?= $editando ? 'Editar material' : 'Nuevo material' ?></h1>

<?php mostrar_errores($errores); ?>
<br>
<form class="formulario" method="post" novalidate>
    <label for="nombre">Nombre</label>
    <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= e($datos['nombre']) ?>">

    <label for="unidad">Unidad de medida</label>
    <input type="text" id="unidad" name="unidad" maxlength="30" placeholder="Bulto, m³, pieza, kg..." value="<?= e($datos['unidad']) ?>">

    <label for="cantidad">Cantidad</label>
    <input type="number" id="cantidad" name="cantidad" step="0.01" min="0" value="<?= e($datos['cantidad']) ?>">

    <label for="costo_unitario">Costo unitario</label>
    <input type="number" id="costo_unitario" name="costo_unitario" step="0.01" min="0" value="<?= e($datos['costo_unitario']) ?>">

    <div class="botones">
        <button type="submit" class="boton boton-chico">GUARDAR</button>
        <a class="boton boton-chico" href="materiales.php">CANCELAR</a>
    </div>
</form>

<?php require 'includes/footer.php'; ?>
