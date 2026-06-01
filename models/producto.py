from typing import Any, TypedDict

from sqlalchemy.orm import Mapped, mapped_column

from db import db


class ProductoDict(TypedDict):
    id: int
    nombre: str
    descripcion: Any
    codigo: str
    imei: str | None
    id_categoria: int
    id_proveedor: Any
    precio_unitario_actual_dolares: Any
    cantidad_disponible: Any
    dias_garantia: Any
    dias_apartado: Any
    imagen_url: Any


class Producto(db.Model):
    __tablename__: str = "productos"

    id: Mapped[int] = mapped_column(db.Integer, primary_key=True)
    nombre: Mapped[str] = mapped_column(db.String, nullable=False)
    descripcion = mapped_column(db.Text)
    codigo: Mapped[str] = mapped_column(db.String, unique=True, nullable=False)
    imei: Mapped[str | None] = mapped_column(db.String, nullable=True)
    id_categoria: Mapped[int] = mapped_column(
        db.Integer, db.ForeignKey("categorias.id"), nullable=False
    )
    id_proveedor = mapped_column(db.Integer, db.ForeignKey("proveedores.id"))
    precio_unitario_actual_dolares = mapped_column(
        db.Numeric(10, 2), nullable=False
    )
    cantidad_disponible = mapped_column(db.Integer)
    dias_garantia = mapped_column(db.Integer)
    dias_apartado = mapped_column(db.Integer)
    imagen_url = mapped_column(db.String)

    # Relaciones
    detalles_ventas = db.relationship("DetalleVenta", backref="producto")
    detalles_compras = db.relationship("DetalleCompra", backref="producto")

    def to_dict(self) -> ProductoDict:
        return {
            "id": self.id,
            "nombre": self.nombre,
            "descripcion": self.descripcion,
            "codigo": self.codigo,
            "imei": self.imei,
            "id_categoria": self.id_categoria,
            "id_proveedor": self.id_proveedor,
            "precio_unitario_actual_dolares": str(
                self.precio_unitario_actual_dolares
            ),
            "cantidad_disponible": self.cantidad_disponible,
            "dias_garantia": self.dias_garantia,
            "dias_apartado": self.dias_apartado,
            "imagen_url": self.imagen_url,
        }

    @classmethod
    def obtener_cantidad_productos_bajo_stock(cls) -> int:
        return cls.query.filter(cls.cantidad_disponible <= 5).count()
