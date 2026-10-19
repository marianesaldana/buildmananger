<?php
// US2 · Formato para registrar (Nuevo) o modificar (Editar) un gasto.
// Sin ?id= es alta; con ?id=N es edición.
require 'config/db.php';
require_once 'includes/funciones.php';

$pagina = 'gastos';
$db = conexion();

$id = (int) ($_GET['id'] ?? 0);
$editando = $id > 0;
$errores = [];
$datos = ['concepto' => '', 'monto' => '', 'fecha' => date('Y-m-d')];

if ($editando) {
    $consulta = $db->prepare('SELECT concepto, monto, fecha FROM Gastos WHERE id_gasto = ?');
    $consulta->execute([$id]);
    $fila = $consulta->fetch();
    if (!$fila) {
        redirigir('gastos.php', 'El gasto ya no existe.');
    }
    $datos = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos['concepto'] = trim($_POST['concepto'] ?? '');
    $datos['monto']    = trim($_POST['monto'] ?? '');
    $datos['fecha']    = trim($_POST['fecha'] ?? '');

    // Validaciones
    if ($datos['concepto'] === '') {
        $errores[] = 'El concepto es obligatorio.';
    }
    if ($datos['monto'] === '') {
        $errores[] = 'El monto es obligatorio.';
    } elseif (!is_numeric($datos['monto']) || (float) $datos['monto'] <= 0) {
        $errores[] = 'El monto debe ser un número mayor a 0.';
    }
    $fecha_valida = DateTime::createFromFormat('Y-m-d', $datos['fecha']);
    if ($datos['fecha'] === '') {
        $errores[] = 'La fecha es obligatoria.';
    } elseif (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $datos['fecha']) {
        $errores[] = 'La fecha no es válida.';
    }

    // Guardar
    if (!$errores) {
        if ($editando) {
            $sql = $db->prepare('UPDATE Gastos SET concepto = ?, monto = ?, fecha = ? WHERE id_gasto = ?');
            $sql->execute([$datos['concepto'], $datos['monto'], $datos['fecha'], $id]);
            redirigir('gastos.php', 'Gasto actualizado correctamente.');
        } else {
            $sql = $db->prepare('INSERT INTO Gastos (concepto, monto, fecha) VALUES (?, ?, ?)');
            $sql->execute([$datos['concepto'], $datos['monto'], $datos['fecha']]);
            redirigir('gastos.php', 'Gasto registrado correctamente.');
        }
    }
}

require 'includes/header.php';
?>
<h1><?= $editando ? 'Editar gasto' : 'Nuevo gasto' ?></h1>

<?php mostrar_errores($errores); ?>
<br>
<form class="formulario" method="post" novalidate>
    <label for="concepto">Concepto</label>
    <input type="text" id="concepto" name="concepto" maxlength="150" value="<?= e($datos['concepto']) ?>">

    <label for="monto">Monto</label>
    <input type="number" id="monto" name="monto" step="0.01" min="0" value="<?= e($datos['monto']) ?>">

    <label for="fecha">Fecha</label>
    <input type="date" id="fecha" name="fecha" value="<?= e($datos['fecha']) ?>">

    <div class="botones">
        <button type="submit" class="boton boton-chico">GUARDAR</button>
        <a class="boton boton-chico" href="gastos.php">CANCELAR</a>
    </div>
</form>

<?php require 'includes/footer.php'; ?>
