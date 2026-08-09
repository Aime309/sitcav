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
        Schema::create('sucursales', static function (Blueprint $table): void {
            $table->string('slug')->primary();

            $table
                ->foreignIdFor(Negocio::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('nombre')->unique();
            $table->string('direccion')->unique();
            $table->string('telefono')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
