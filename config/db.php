<?php
// Conexión a PostgreSQL (base de datos Empresa). Cambia estos valores por los tuyos.
const DB_HOST = 'localhost';
const DB_PORT = '5432';
const DB_NAME = 'empresa';
const DB_USER = 'postgres';
const DB_PASS = 'postgres';

function conexion(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
