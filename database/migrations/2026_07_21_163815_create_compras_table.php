<?php

declare(strict_types=1);

use App\Models\Compra;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', static function (Blueprint $table): void {
            $table->id();

            $table
                ->foreignIdFor(Negocio::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignIdFor(Sucursal::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignIdFor(Proveedor::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });

        Schema::create(
            'compras_detalles',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Compra::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table
                    ->foreignIdFor(Producto::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table->integer('cantidad')->unique();
                $table->float('precio')->unique();
                $table->timestamps();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
        Schema::dropIfExists('compras_detalles');
    }
};
