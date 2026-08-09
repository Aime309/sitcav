<?php

declare(strict_types=1);

use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Reserva;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', static function (Blueprint $table): void {
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
                ->foreignIdFor(Cliente::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignIdFor(Reserva::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });

        Schema::create(
            'ventas_detalles',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Venta::class)
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
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('ventas_detalles');
    }
};
