<?php

declare(strict_types=1);

use App\Models\Negocio;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'proveedores',
            static function (Blueprint $table): void {
                $table->id();

                $table
                    ->foreignIdFor(Negocio::class)
                    ->constrained()
                    ->cascadeOnDelete()
                    ->cascadeOnUpdate();

                $table->string('nombre')->unique();
                $table->string('rif')->unique();
                $table->string('telefono')->unique();
                $table->string('direccion')->unique();
                $table->binary('imagen')->unique();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
