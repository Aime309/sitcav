<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negocios', static function (Blueprint $table): void {
            $table->string('slug')->primary();
            $table->string('nombre')->unique();
            $table->string('rif')->unique();
            $table->string('direccion')->unique();
            $table->string('telefono')->unique();
            $table->boolean('carga_inicial_abierta')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negocios');
    }
};
