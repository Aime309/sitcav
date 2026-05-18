# Copilot instructions

## Commands

- Install dependencies: `py -m pip install -e ".[dev]"`
- Start the application (Backend & Frontend): `py src\app.py`
- Open the application at `http://localhost:5000`
- Quick API smoke checks:
  - `curl http://localhost:5000/api/productos`
  - `curl http://localhost:5000/api/cotizacion/actual`
- Checked-in automated test: `pytest tests\test_user_data.py`

There is no configured lint tool, build pipeline, or formal `pytest`/`unittest` suite in this repository.

## Architecture

- `src\app.py` is the entire Flask backend: app configuration, lightweight schema auto-migration, database initialization/seeding, file upload serving, PDF endpoints, and all API routes live in one file. There are no Flask blueprints or separate service layers.
- `src\models.py` is the shared domain model layer. It covers users and roles, geographic lookup tables, business metadata, products/categories/providers, sales and purchases, payment types/payments, exchange rates, layaway (`apartados`), inventory movement history, and refunds.
- `index.html` + `src\static\app.js` form a single-page frontend with inline section switching instead of routing. Navigation and dashboard cards are shown/hidden from `setupRolePermissions()` based on the logged-in role.
- Local development uses a single Flask process on port `5000` which serves both the API and the frontend. Access the application at `http://localhost:5000`.
- Runtime data is stored under `instance/`: the SQLite database (`system_data.db`), uploaded product/profile images, SQL backups, and generated PDFs. `src\pdf_generator.py` contains most PDF builders, while refund PDF generation still lives directly in `src\app.py`.

## Key conventions

- Keep the Spanish domain vocabulary consistent across backend models, JSON payloads, and frontend code. Existing route names, field names, and UI labels use terms like `usuarios`, `clientes`, `cotizacion`, `apartados`, `inventario`, and `reembolsos`.
- `py src\app.py` is not a pure server start. Startup runs `check_and_migrate_db()` and `initialize_database()`, so changes near app startup can affect both existing SQLite files and first-run seed data.
- Monetary and exchange-rate values are modeled with SQLAlchemy `Numeric`/`Decimal` fields. Preserve that pattern in the backend and only convert to JSON-safe `float`/`str` values at serialization boundaries.
- Sales, purchases, layaway, inventory adjustments, and refunds are coupled business flows. When changing any of those endpoints, check the stock side effects (`Producto.cantidad_disponible`), `MovimientoInventario` records, and historical exchange-rate usage together.
- Refunds should use the historical rate stored on `Venta.cotizacion_dolar_bolivares`; only fall back to the latest `Cotizacion` when older sales do not have a saved rate.
- Product and profile images are stored as files under `instance\uploads\...` and exposed through Flask routes that return relative URLs like `/uploads/productos/<filename>` and `/uploads/profiles/<filename>`. Frontend code expects those relative URLs and uses them directly.
- `src\static\app.js` is a large, manually merged script with duplicate later definitions for some functions (for example `loadDashboardStats`, `loadEmpleados`, and recovery helpers). Before editing a frontend behavior, search the whole file and treat the last matching function definition as the one that actually runs in the browser.
