<?php

declare(strict_types=1);

use App\Models\Carrito;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carritos', static function (Blueprint $table): void {
            $table->id();

            $table
                ->foreignIdFor(Cliente::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });

        Schema::create(
            'carritos_detalles',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Carrito::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table
                    ->foreignIdFor(Producto::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

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

                $table->integer('cantidad');
                $table->timestamps();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('carritos');
        Schema::dropIfExists('carritos_detalles');
    }
};
