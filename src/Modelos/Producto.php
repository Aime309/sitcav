<?php

namespace SITCAV\Modelos;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read ?Collection<int, DetalleVenta> $ventas
 * @property-read ?Collection<int, DetalleCompra> $compras
 */
final class Producto extends Model
{
  protected $table = 'productos';
  public $timestamps = false;

  private const PATRONES = [
    'nombre' => '/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\-\_\+\'\"\s]+$/',
  ];

  function categoria(): BelongsTo
  {
    return $this->belongsTo(CategoriaProducto::class, 'id_categoria');
  }

  function proveedor(): BelongsTo
  {
    return $this->belongsTo(Proveedor::class, 'id_proveedor');
  }

  function compras(): HasMany
  {
    return $this->hasMany(DetalleCompra::class, 'id_producto');
  }

  function ventas(): HasMany
  {
    return $this->hasMany(DetalleVenta::class, 'id_producto');
  }

  function marca(): BelongsTo
  {
    return $this->belongsTo(Marca::class, 'id_marca');
  }

  function __set($key, $value)
  {
    switch ($key) {
      case 'codigo':
        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $value)) {
          throw new Exception('El código del producto solo puede contener letras, números y el guión');
        }

        $value = mb_strtoupper($value);

        break;

      case 'nombre':
        if (!preg_match(self::PATRONES['nombre'], $value)) {
          throw new Exception('El nombre del producto solo puede contener letras, números, espacios y los caracteres - _ + \' "');
        }

        $value = str_replace('  ', ' ', $value);

        break;
      case 'descripcion':
        if (!preg_match(self::PATRONES['nombre'], $value)) {
          throw new Exception('La descripción del producto solo puede contener letras, números, espacios y los caracteres - _ + \' "');
        }

        $value = str_replace('  ', ' ', $value);

        break;
      case 'precio_unitario_actual_dolares':
        if (!is_numeric($value)) {
          throw new Exception('El precio del producto debe ser un número');
        }

        if ($value <= 0) {
          throw new Exception('El precio del producto debe ser mayor a 0');
        }

        break;
      case 'cantidad_disponible':
        if ($value <= 0) {
          throw new Exception('La cantidad del producto debe ser mayor a 0');
        }

        break;
      case 'dias_garantia':
        if ($value <= 0) {
          throw new Exception('Los días de garantía del producto deben ser mayores a 0');
        }

        break;

      case 'dias_apartado':
        if ($value <= 0) {
          throw new Exception('Los días de apartado del producto deben ser mayores a 0');
        }

        break;
    }

    parent::__set($key, $value);
  }
}
