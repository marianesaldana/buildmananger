<?php
// Encabezado común. Cada página define $pagina antes de incluir este archivo.
// En la página de inicio ($pagina vacío) no se muestra el menú superior.
require_once __DIR__ . '/funciones.php';

$pagina = $pagina ?? '';
$menu = [
    'empleados'  => ['Empleados',  'empleados.php'],
    'gastos'     => ['Gastos',     'gastos.php'],
    'materiales' => ['Materiales', 'materiales.php'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuildManager</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<?php if ($pagina): ?>
<nav class="menu">
    <a class="boton boton-chico" href="index.php">Inicio</a>
    <?php foreach ($menu as $clave => [$texto, $url]): ?>
        <a class="boton boton-chico <?= $clave === $pagina ? 'activo' : '' ?>" href="<?= $url ?>"><?= $texto ?></a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>
