<?php

declare(strict_types=1);

namespace SITCAV\Controladores\API;

use Flight;
use SITCAV\Modelos\CategoriaProducto;
use SITCAV\Modelos\Marca;
use SITCAV\Modelos\Producto;
use SITCAV\Modelos\Proveedor;
use SITCAV\Modelos\UsuarioAutenticado;
use Throwable;

final readonly class ControladorDeProductos
{
  function __construct(private UsuarioAutenticado $usuarioAutenticado) {}

  function listarProductos(): void
  {
    $productos = Producto::with(['categoria', 'marca'])->get();

    Flight::json($productos);
  }

  function mostrarDetallesDelProducto(int $id): void
  {
    try {
      $producto = Producto::with(['categoria', 'marca', 'ventas'])->findOrFail($id);

      Flight::json($producto);
    } catch (Throwable) {
      Flight::halt(404, 'Producto no encontrado');
    }
  }

  static function registrarProducto(): void
  {
    $datos = Flight::request()->data;

    $error = match (true) {
      !$datos->nombre => 'El nombre es requerido',
      !$datos->precio => 'El precio es requerido',
      !$datos->cantidad => 'La cantidad es requerida',
      !$datos->dias_apartado => 'Los días de apartado son requeridos',
      !$datos->id_categoria => 'La categoría es requerida',
      !$datos->id_proveedor => 'El proveedor es requerido',
      default => ''
    };

    if ($error) {
      Flight::halt(400, $error);
    }

    try {
      CategoriaProducto::query()->findOrFail($datos->id_categoria);
    } catch (Throwable) {
      Flight::halt(400, 'La categoría no existe');
    }

    try {
      Proveedor::query()->findOrFail($datos->id_proveedor);
    } catch (Throwable) {
      Flight::halt(400, 'El proveedor no existe');
    }

    $datos->id_marca ??= 0;

    if ($datos->id_marca) {
      try {
        Marca::query()->findOrFail($datos->id_marca);
      } catch (Throwable) {
        Flight::halt(400, 'La marca no existe');
      }
    }

    try {
      $producto = new Producto;

      if ($datos->codigo) {
        $producto->codigo = $datos->codigo;
      }

      $producto->nombre = $datos->nombre;

      if ($datos->descripcion) {
        $producto->descripcion = $datos->descripcion;
      }

      // TODO: url de imagen
      $producto->precio_unitario_actual_dolares = $datos->precio;
      $producto->cantidad_disponible = $datos->cantidad;

      if ($datos->dias_garantia) {
        $producto->dias_garantia = $datos->dias_garantia;
      }

      $producto->dias_apartado = $datos->dias_apartado;
      $producto->id_categoria = $datos->id_categoria;
      $producto->id_proveedor = $datos->id_proveedor;
      $producto->id_marca = $datos->id_marca;

      $producto->save();

      Flight::json($producto);
    } catch (Throwable $error) {
      Flight::halt(409, match (true) {
        str_contains($error->getMessage(), 'nombre') => 'El nombre ya existe',
        default => $error->getMessage()
      });
    }
  }

  static function actualizarProducto(int $id): void
  {
    $datos = Flight::request()->data;

    try {
      $producto = Producto::query()->findOrFail($id);
    } catch (Throwable) {
      Flight::halt(404, 'Producto no encontrado');
    }

    if ($datos->id_proveedor) {
      try {
        Proveedor::query()->findOrFail($datos->id_proveedor);
      } catch (Throwable) {
        Flight::halt(400, 'El proveedor no existe');
      }
    }

    if ($datos->id_categoria) {
      try {
        CategoriaProducto::query()->findOrFail($datos->id_categoria);
      } catch (Throwable) {
        Flight::halt(400, 'La categoría no existe');
      }
    }

    if ($datos->id_marca) {
      try {
        Marca::query()->findOrFail($datos->id_marca);
      } catch (Throwable) {
        Flight::halt(400, 'La marca no existe');
      }
    }

    try {
      if ($datos->nombre) {
        $producto->nombre = $datos->nombre;
      }

      if ($datos->descripcion) {
        $producto->descripcion = $datos->descripcion;
      }

      if ($datos->codigo) {
        $producto->codigo = $datos->codigo;
      }

      if ($datos->precio) {
        $producto->precio_unitario_actual_dolares = $datos->precio;
      }

      if ($datos->cantidad) {
        $producto->cantidad_disponible = $datos->cantidad;
      }

      if ($datos->dias_garantia) {
        $producto->dias_garantia = $datos->dias_garantia;
      }

      if ($datos->dias_apartado) {
        $producto->dias_apartado = $datos->dias_apartado;
      }

      if ($datos->id_categoria) {
        $producto->id_categoria = $datos->id_categoria;
      }

      if ($datos->id_proveedor) {
        $producto->id_proveedor = $datos->id_proveedor;
      }

      if ($datos->id_marca) {
        $producto->id_marca = $datos->id_marca;
      }

      $producto->save();

      Flight::json($producto);
    } catch (Throwable $error) {
      Flight::halt(409, match (true) {
        str_contains($error->getMessage(), 'nombre') => 'El nombre ya existe',
        default => $error->getMessage()
      });
    }
  }

  static function eliminarProducto(int $id): void
  {
    try {
      $producto = Producto::with(['ventas', 'compras'])->findOrFail($id);
    } catch (Throwable) {
      Flight::halt(404, 'Producto no encontrado');
    }

    if ($producto->ventas->isNotEmpty() || $producto->compras->isNotEmpty()) {
      Flight::halt(409, 'No se puede eliminar el producto porque tiene ventas o compras registradas');
    }

    $producto->delete();
    Flight::json($producto);
  }
}
