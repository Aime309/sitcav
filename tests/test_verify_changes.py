from pathlib import Path

from flask.testing import FlaskClient
import pytest

from models import Cliente, Negocio, Producto


def test_flujo_venta_y_reembolso(client: FlaskClient):
    db_path = Path("instance") / "system_data.db"

    if not db_path.exists():
        pytest.skip("DB not found")

    login_response = client.post(
        "/login",
        json={"usuario": "12345678", "contrasena": "test1"},
    )
    if login_response.status_code != 200:
        pytest.skip("No se pudo autenticar usuario de prueba")

    login_data = login_response.get_json()
    user_id = login_data["usuario_id"]

    with client.application.app_context():
        cliente = Cliente.query.order_by(Cliente.id.asc()).first()
        producto = (
            Producto.query.filter(Producto.cantidad_disponible > 0)
            .order_by(Producto.id.asc())
            .first()
        )
        negocio = Negocio.query.first()

        if not cliente or not producto:
            pytest.skip("No hay cliente o producto disponible para prueba")

        cliente_id = cliente.id
        producto_id = producto.id
        negocio_original = None
        if negocio:
            negocio_original = {
                "nombre": negocio.nombre,
                "rif": negocio.rif,
                "telefono": negocio.telefono,
                "direccion": negocio.direccion,
            }

    venta_id = None
    reembolso_id = None

    try:
        if negocio_original:
            negocio_update = {
                "nombre": negocio_original["nombre"],
                "rif": negocio_original["rif"],
                "telefono": negocio_original["telefono"],
                "direccion": "Calle Verificacion 123",
            }
            update_response = client.put("/api/negocio", json=negocio_update)
            assert update_response.status_code == 200

        venta_response = client.post(
            "/api/ventas",
            json={
                "id_cliente": cliente_id,
                "id_vendedor": user_id,
                "detalles": [{"id_producto": producto_id, "cantidad": 1}],
            },
        )
        assert venta_response.status_code == 201

        venta_data = venta_response.get_json()["venta"]
        venta_id = venta_data["id"]
        assert "cotizacion_dolar_bolivares" in venta_data

        reembolso_response = client.post(
            "/api/reembolsos",
            json={
                "id_venta": venta_id,
                "id_usuario": user_id,
                "monto_dolares": 5.0,
                "motivo": "Verification Test",
            },
        )
        assert reembolso_response.status_code == 201

        reembolso_data = reembolso_response.get_json()["reembolso"]
        reembolso_id = reembolso_data["id"]

        list_response = client.get("/api/reembolsos")
        assert list_response.status_code == 200
        reembolsos = list_response.get_json()
        assert any(r["id"] == reembolso_id for r in reembolsos)
    finally:
        if reembolso_id is not None:
            client.delete(f"/api/reembolsos/{reembolso_id}")

        if venta_id is not None:
            client.delete(f"/api/ventas/{venta_id}")

        if negocio_original:
            client.put("/api/negocio", json=negocio_original)
