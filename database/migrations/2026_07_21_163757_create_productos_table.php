<?php

declare(strict_types=1);

use App\Models\Negocio;
use App\Models\Producto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', static function (Blueprint $table): void {
            $table->id();

            $table
                ->foreignIdFor(Negocio::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('nombre')->unique();
            $table->string('descripcion')->unique();
            $table->float('precio')->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create(
            'productos_imagenes',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Producto::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table->binary('imagen')->unique();
                $table->timestamps();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
        Schema::dropIfExists('productos_imagenes');
    }
};
