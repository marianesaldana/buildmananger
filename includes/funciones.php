<?php
// Funciones de apoyo usadas en todas las páginas.

// Escapa texto para mostrarlo en HTML.
function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// Redirige a otra página (con mensaje opcional) y termina.
function redirigir(string $pagina, string $mensaje = ''): void
{
    $url = $pagina . ($mensaje !== '' ? '?msg=' . urlencode($mensaje) : '');
    header('Location: ' . $url);
    exit;
}

// Muestra el mensaje de éxito que llega por la URL (?msg=...).
function mostrar_mensaje(): void
{
    if (!empty($_GET['msg'])) {
        echo '<p class="mensaje">' . e($_GET['msg']) . '</p>';
    }
}

// Muestra la lista de errores de validación de un formulario.
function mostrar_errores(array $errores): void
{
    if (!$errores) {
        return;
    }
    echo '<div class="errores"><strong>Revisa los siguientes datos:</strong><ul>';
    foreach ($errores as $error) {
        echo '<li>' . e($error) . '</li>';
    }
    echo '</ul></div>';
}

function dinero($monto): string
{
    return '$' . number_format((float) $monto, 2);
}
