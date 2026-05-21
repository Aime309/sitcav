from collections.abc import Generator
from typing import Any

import pytest
from flask import Flask
from flask.testing import FlaskClient, FlaskCliRunner

from app import create_app


@pytest.fixture()
def app() -> Generator[Flask, Any, None]:
    app = create_app()

    app.config.update(
        {
            "TESTING": True,
        }
    )

    # other setup can go here

    yield app

    # clean up / reset resources here


@pytest.fixture()
def client(app: Flask) -> FlaskClient:
    return app.test_client()


@pytest.fixture()
def runner(app: Flask) -> FlaskCliRunner:
    return app.test_cli_runner()
