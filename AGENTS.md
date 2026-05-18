# Repository Guidelines

## Project Structure & Module Organization

This repository is a flat Flask + vanilla JavaScript administrative system. Core backend code lives in `src\app.py`, database models in `src\models.py`, and PDF creation in `src\pdf_generator.py`. The frontend entry point is `index.html`, with most UI behavior in `src\static\app.js`; feature patches and migration helpers are kept as standalone scripts such as `migrate_*.py`, `patch_*.py`, and `verify_*.py`.

Runtime data is stored under `instance/`, including `system_data.db`, uploaded product images, generated invoices, reports, refunds, and layaway PDFs. Treat `instance/` content as local/generated data unless a task explicitly requires changing fixtures.

## Build, Test, and Development Commands

Create and install dependencies with Python 3.12:

```powershell
py -m venv .venv
.\.venv\Scripts\Activate.ps1
py -m pip install -e ".[dev]"
```

Run the backend API (now serves both frontend and API):

```powershell
py src\app.py
```

Open `http://localhost:5000`. Do not open `index.html` directly, because browser `file://` access causes CORS failures.

## Coding Style & Naming Conventions

Follow PEP 8 for Python: 4-space indentation, `snake_case` functions and variables, and clear names for migration or verification scripts. Keep Flask route handlers explicit and return JSON consistently. For JavaScript, use modern ES6 syntax, `const`/`let`, `async`/`await`, and descriptive camelCase names for DOM and API helpers.

## Testing Guidelines

There is no formal pytest suite in this snapshot. Use the existing verification scripts for focused checks, for example:

```powershell
pytest tests\test_verify_migration.py
pytest tests\test_user_data.py
```

For UI/API changes, manually test the affected workflow with both servers running and check the browser console plus the Flask terminal output.

## Commit & Pull Request Guidelines

Recent commits use concise imperative messages, sometimes with a scope prefix, such as `docs: add GEMINI.md project documentation`, `Set Python version to 3.12`, and `Remove trailing slashes from API_BASE_URL`. Keep commits focused on one concern.

Pull requests should include a short summary, changed workflows or endpoints, migration steps if database behavior changes, and manual verification notes. Include screenshots for visible UI changes and mention any generated files intentionally added or ignored.

## Security & Configuration Tips

Do not commit real credentials, production databases, or generated customer documents. Review `credentials.txt`, `users_dump.txt`, and `instance/` carefully before sharing changes. Use local SQLite data only for development.
