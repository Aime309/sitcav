# Copilot Instructions for this Repository

## Build, Test, and Lint Commands

- **Install dependencies:**
  ```powershell
  uv sync
  ```
- **Start development server:**
  ```powershell
  vercel dev
  ```
  Access at: http://localhost:3000
- **Run all tests:**
  ```powershell
  uv run pytest
  ```
- **Run a single test:**
  ```powershell
  uv run pytest tests/test_user_data.py
  ```
- **Quick API checks:**
  ```powershell
  curl http://localhost:3000/api/productos
  curl http://localhost:3000/api/cotizacion/actual
  ```

## High-Level Architecture

- **Backend:** Python 3.12.x, Flask, SQLAlchemy (SQLite). Entry point: `main.py`.
- **Frontend:** Served via Jinja2 templates (`templates/index.html`), main logic in `static/app.js`.
- **PDF Generation:** `pdf_generator.py` uses ReportLab to create invoices in `instance/facturas/`.
- **Database:** SQLite file at `instance/system_data.db`, initialized via `db.py` and `schema.sql`.
- **API:** Main routes in `api.py`, authentication in `auth.py`, file uploads in `uploads.py`.
- **Role-based UI:** Module visibility is controlled by `setupRolePermissions()` in `static/app.js`.
- **Testing:** Pytest-based, with fixtures in `tests/conftest.py` and sample tests in `tests/`.
- **Deployment:** Vercel config in `vercel.json`.

## Key Conventions

- **Language:** Use Spanish for model, route, and variable names.
- **Python:** Follow PEP 8. Use clear, descriptive names.
- **JavaScript:** Use ES6+, async/await, and descriptive names.
- **Data:** All local data (uploads, PDFs, DB) is stored in `instance/` (never commit these files).
- **Frontend:** Never open `index.html` directly; always use the dev server.
- **Database:** On first run, `main.py` auto-migrates and seeds the DB if needed.
- **Sensitive Data:** Never commit credentials or local data.

---

This file summarizes build/test commands, architecture, and conventions for Copilot and future contributors. Would you like to adjust anything or add coverage for other areas (e.g., advanced testing, CI, or deployment)?
