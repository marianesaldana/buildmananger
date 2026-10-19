<?php
// US1 · Formato para registrar (Nuevo) o modificar (Editar) un empleado.
// Sin ?rfc= es alta; con ?rfc=XXXX es edición.
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'empleados';
$db = conexion();

$rfc_editar = trim($_GET['rfc'] ?? '');
$editando = $rfc_editar !== '';
$errores = [];
$datos = ['nombre' => '', 'rfc' => '', 'puesto' => '', 'salario' => ''];

// Al editar, se cargan los datos actuales del empleado.
if ($editando) {
    $consulta = $db->prepare('SELECT Nombre, RFC, Puesto, Salario FROM Empleados WHERE RFC = ?');
    $consulta->execute([$rfc_editar]);
    $fila = $consulta->fetch();
    if (!$fila) {
        redirigir('empleados.php', 'El empleado ya no existe.');
    }
    $datos = [
        'nombre'  => $fila['nombre'],
        'rfc'     => trim($fila['rfc']),
        'puesto'  => $fila['puesto'],
        'salario' => $fila['salario'],
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos['nombre']  = trim($_POST['nombre'] ?? '');
    $datos['puesto']  = trim($_POST['puesto'] ?? '');
    $datos['salario'] = trim($_POST['salario'] ?? '');
    if (!$editando) {
        $datos['rfc'] = strtoupper(trim($_POST['rfc'] ?? ''));
    }

    // Validaciones
    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }
    if (!$editando) {
        if ($datos['rfc'] === '') {
            $errores[] = 'El RFC es obligatorio.';
        } elseif (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $datos['rfc'])) {
            $errores[] = 'El RFC no tiene un formato válido (12 o 13 caracteres, ej. PELJ900101ABC).';
        } else {
            $existe = $db->prepare('SELECT 1 FROM Empleados WHERE RFC = ?');
            $existe->execute([$datos['rfc']]);
            if ($existe->fetch()) {
                $errores[] = 'Ya existe un empleado registrado con ese RFC.';
            }
        }
    }
    if ($datos['puesto'] === '') {
        $errores[] = 'El puesto es obligatorio.';
    }
    if ($datos['salario'] === '') {
        $errores[] = 'El salario es obligatorio.';
    } elseif (!is_numeric($datos['salario']) || (float) $datos['salario'] <= 0) {
        $errores[] = 'El salario debe ser un número mayor a 0.';
    }

    // Guardar
    if (!$errores) {
        if ($editando) {
            $sql = $db->prepare('UPDATE Empleados SET Nombre = ?, Puesto = ?, Salario = ? WHERE RFC = ?');
            $sql->execute([$datos['nombre'], $datos['puesto'], $datos['salario'], $rfc_editar]);
            redirigir('empleados.php', 'Empleado actualizado correctamente.');
        } else {
            $sql = $db->prepare('INSERT INTO Empleados (Nombre, RFC, Puesto, Salario) VALUES (?, ?, ?, ?)');
            $sql->execute([$datos['nombre'], $datos['rfc'], $datos['puesto'], $datos['salario']]);
            redirigir('empleados.php', 'Empleado registrado correctamente.');
        }
    }
}

require 'includes/header.php';
?>
<h1><?= $editando ? 'Editar empleado' : 'Nuevo empleado' ?></h1>

<?php mostrar_errores($errores); ?>
<br>
<form class="formulario" method="post" novalidate>
    <label for="nombre">Nombre completo</label>
    <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= e($datos['nombre']) ?>">

    <label for="rfc">RFC</label>
    <input type="text" id="rfc" name="rfc" maxlength="13" value="<?= e($datos['rfc']) ?>" <?= $editando ? 'readonly' : '' ?>>

    <label for="puesto">Puesto</label>
    <input type="text" id="puesto" name="puesto" maxlength="100" value="<?= e($datos['puesto']) ?>">

    <label for="salario">Salario</label>
    <input type="number" id="salario" name="salario" step="0.01" min="0" value="<?= e($datos['salario']) ?>">

    <div class="botones">
        <button type="submit" class="boton boton-chico">GUARDAR</button>
        <a class="boton boton-chico" href="empleados.php">CANCELAR</a>
    </div>
</form>

<?php require 'includes/footer.php'; ?>
