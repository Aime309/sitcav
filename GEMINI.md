# Gemini CLI Context: Sistema de Gestión Administrativo

## Project Overview
This is a comprehensive administrative management system designed for small businesses (like tech stores). It features a Python (Flask) backend and a Vanilla JavaScript frontend.

### Core Technologies
- **Backend:** Python 3.8+, Flask, Flask-SQLAlchemy (SQLite), Flask-CORS.
- **Frontend:** HTML5, CSS3, Vanilla JavaScript (Modular).
- **PDF Generation:** ReportLab.
- **Security:** Werkzeug (Password hashing), Security Questions for recovery.
- **Database:** SQLite (ORM via SQLAlchemy).

### Key Modules
- **Dashboard:** Real-time statistics and stock alerts.
- **Security:** User roles (Encargado, Empleado Superior, Vendedor), Profile management with photos.
- **Inventory:** CRUD for products with category management, IMEI tracking, and image support.
- **CRM/SRM:** Customer and Supplier management with geographic context (State/City/Sector).
- **Sales & Purchases:** Transaction history, invoices (PDF), and stock synchronization.
- **Layaway System (Apartados):** Reservations with multiple payments/installments.
- **Financials:** Currency exchange rates (Bolívares/USD), multiple payment types.
- **Reports:** Professional PDF generation for invoices, receipts, and sales reports.
- **Utilities:** Database backup and restoration.

---

## Development Environment

### Prerequisites
- Python 3.8+
- Modern Web Browser

### Key Files
- `src/app.py`: Main Flask application server.
- `src/models.py`: Database schema (16 tables).
- `src/pdf_generator.py`: Logic for creating PDFs using ReportLab.
- `src/templates/index.html`: Frontend entry point (rendered by Flask).
- `src/static/app.js`: Main frontend logic.
- `pyproject.toml`: Project dependencies and editable install metadata.

### Running the Project
The application now runs as a single process:

1.  **Start the Server:**
    ```bash
    python src/app.py
    ```
    Runs on `http://localhost:5000`. Access the application by visiting `http://localhost:5000` in your browser.

---

## Architecture & Conventions

### Database Schema (SQLAlchemy Models)
The system uses a highly normalized schema with relationships for:
- **Users (`Usuario`):** Roles, profile data, and security questions.
- **Location:** `Estado` -> `Localidad` -> `Sector`.
- **Products:** Linked to `Categoria` and `Proveedor`. Supports `imei` and `imagen_url`.
- **Transactions:** `Venta`, `DetalleVenta`, `Compra`, `DetalleCompra`, `Reembolso`.
- **Layaway:** `Apartado`, `DetalleApartado`, `PagoApartado`.
- **Inventory History:** `MovimientoInventario`.

### API Design
- **Base URL:** `http://localhost:5000` (Unified Frontend and API)
- **Authentication:** Cédula-based login (`/login`). Uses `localStorage` for session persistence.
- **Data Format:** JSON for requests and responses.
- **Media Handling:** Images are uploaded to `instance/uploads/` and served via static routes.

### PDF Workflow
PDFs are generated on-the-fly on the backend and sent to the client as attachments.
- **Storage:** Generated PDFs are temporarily stored in `instance/facturas/`, `instance/reportes/`, etc.

### Coding Style
- **Python:** PEP 8, Docstrings for modules and complex functions.
- **JavaScript:** ES6+, uses `async/await` for fetch calls, DOM manipulation via vanilla JS.
- **Styles:** Custom CSS with a modern, responsive look (using Bootstrap-like patterns but mostly custom).

---

## Common Tasks & Maintenance

- **Reset Database:** Delete `instance/system_data.db` and restart `src/app.py`. Initial seed data will be recreated automatically.
- **Backups:** Triggered via the UI, backups are saved as `.sql` (SQL dump) or `.db` copies in `instance/`.
- **Debugging:**
    - Check browser console (F12) for frontend errors.
    - Check terminal output of `src/app.py` for backend/SQL errors.
    - Use `/api/debug/uploads` to verify file system access.
