# BuildManager · Sprint 1

Plataforma de administración para obras de construcción.
Equipo 4A · Gestión de Proyectos · TecNM Celaya

**Meta del Sprint 1:** el administrador registra en un solo lugar sus empleados, gastos y materiales.

## Lo que incluye

| Historia | Módulo | Funciones |
|---|---|---|
| US1 | Empleados | Lista, Nuevo, Editar y Eliminar (nombre, RFC, puesto, salario) |
| US2 | Gastos | Lista con total gastado, Nuevo, Editar y Eliminar (concepto, monto, fecha) |
| US3 | Materiales | Lista con buscador por nombre, Nuevo, Editar y Eliminar (nombre, unidad, cantidad, costo unitario) |

Todos los formatos validan los datos antes de guardar, y Eliminar siempre pide confirmación.

## Cómo correrlo

1. En pgAdmin crea la base de datos `empresa`.
2. Abre el Query Tool sobre `empresa` y ejecuta `sql/buildmanager.sql`. Crea las 3 tablas con datos de prueba.
3. En `config/db.php` pon tu usuario y contraseña de PostgreSQL.
4. Verifica que PHP tenga activada la extensión `pdo_pgsql` (en `php.ini`, quita el `;` de `extension=pdo_pgsql`).
5. En una terminal dentro de esta carpeta ejecuta:

   ```
   php -S localhost:8000
   ```

6. Abre `http://localhost:8000` en el navegador.

## Estructura

```
index.php               Menú principal
empleados.php           Lista de empleados
empleado_form.php       Nuevo / Editar empleado
empleado_eliminar.php   Confirmación para eliminar empleado
gastos.php              Lista de gastos con total
gasto_form.php          Nuevo / Editar gasto
gasto_eliminar.php      Confirmación para eliminar gasto
materiales.php          Lista de materiales con buscador
material_form.php       Nuevo / Editar material
material_eliminar.php   Confirmación para eliminar material
config/db.php           Conexión a PostgreSQL
includes/               Encabezado, pie y funciones comunes
assets/styles.css       Estilos
sql/buildmanager.sql    Tablas y datos de prueba
```

## Antes de subirlo a GitHub

`config/db.php` lleva tu contraseña. Si el repositorio es público, cámbiala por una genérica antes de hacer push.
