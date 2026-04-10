<?php

declare(strict_types=1);

namespace App\Models;

final class Product extends Model
{
  protected static function getTableName(): string
  {
    return 'productos';
  }

  public function countWithLowStock(): int
  {
    return $this
      ->db
      ->query("SELECT COUNT(*) AS count FROM $this->table WHERE cantidad_disponible <= 5")
      ->column();
  }

  public function getReservedQuantity(int $productId): int
  {
    return (int) $this->db->query("
      SELECT SUM(da.cantidad) AS count 
      FROM detalles_apartados da
      JOIN apartados a ON da.id_apartado = a.id
      WHERE da.id_producto = ? AND a.estado = 'activo'
    ", $productId)->column();
  }

  public function adjustStock(int $productId, int $quantity, string $type): void
  {
    $product = $this->db->select($this->table)->find($productId);

    if ($product) {
      $currentStock = $product['cantidad_disponible'];
      $newStock = ($type === 'entrada') ? $currentStock + $quantity : $currentStock - $quantity;
      $this->db->update($this->table)->params(['cantidad_disponible' => $newStock])->where('id', $productId)->execute();
    }
  }
}
