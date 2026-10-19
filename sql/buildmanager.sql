-- =====================================================
-- BuildManager · Sprint 1 · Base de datos (PostgreSQL)
-- Equipo 4A · TecNM Celaya
--
-- Uso:
--   1) CREATE DATABASE empresa;
--   2) Conéctate a "empresa" y ejecuta este script completo.
-- =====================================================

-- ---------- US1 · Empleados ----------
CREATE TABLE IF NOT EXISTS Empleados (
    Nombre  VARCHAR(100)  NOT NULL,
    RFC     CHAR(13)      PRIMARY KEY,
    Puesto  VARCHAR(100)  NOT NULL,
    Salario NUMERIC(10,2) NOT NULL CHECK (Salario > 0)
);

-- ---------- US2 · Gastos ----------
CREATE TABLE IF NOT EXISTS Gastos (
    id_gasto SERIAL        PRIMARY KEY,
    concepto VARCHAR(150)  NOT NULL,
    monto    NUMERIC(10,2) NOT NULL CHECK (monto > 0),
    fecha    DATE          NOT NULL
);

-- ---------- US3 · Materiales ----------
CREATE TABLE IF NOT EXISTS Materiales (
    id_material    SERIAL        PRIMARY KEY,
    nombre         VARCHAR(100)  NOT NULL,
    unidad         VARCHAR(30)   NOT NULL,
    cantidad       NUMERIC(10,2) NOT NULL CHECK (cantidad >= 0),
    costo_unitario NUMERIC(10,2) NOT NULL CHECK (costo_unitario > 0)
);

-- ---------- Datos de prueba ----------
INSERT INTO Empleados (Nombre, RFC, Puesto, Salario) VALUES
    ('Maria Garcia Ruiz', 'GARM950215DEF', 'Contadora', 18000.00),
    ('Juan Perez Lopez',  'PELJ900101ABC', 'Director',  30000.00)
ON CONFLICT (RFC) DO NOTHING;

INSERT INTO Gastos (concepto, monto, fecha) VALUES
    ('Compra de 60 bultos de cemento',      13200.00, '2026-10-12'),
    ('Compra de arena y grava',              4500.00, '2026-10-12'),
    ('Pago de mano de obra a albañiles',     6000.00, '2026-10-13'),
    ('Compra de varilla y alambrón',         7800.00, '2026-10-13'),
    ('Flete para transporte de materiales',  1200.00, '2026-10-14');

INSERT INTO Materiales (nombre, unidad, cantidad, costo_unitario) VALUES
    ('Cemento gris',           'Bulto 50 kg', 60,  220.00),
    ('Arena',                  'm³',          8,   350.00),
    ('Grava',                  'm³',          6,   380.00),
    ('Varilla corrugada 3/8"', 'Pieza',       120, 145.00),
    ('Alambrón',               'kg',          50,  32.00),
    ('Block de concreto',      'Pieza',       500, 14.50);
