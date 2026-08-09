<?php

declare(strict_types=1);

use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Reserva;
use App\Models\Sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', static function (Blueprint $table): void {
            $table->id();

            $table
                ->foreignIdFor(Negocio::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table
                ->foreignIdFor(Cliente::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->enum('estado', ['activa'])->default('activa');
            $table->dateTime('expira_en')->unique();
            $table->timestamps();
        });

        Schema::create(
            'reservas_detalles',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Reserva::class)
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
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
        Schema::dropIfExists('reservas_detalles');
    }
};
