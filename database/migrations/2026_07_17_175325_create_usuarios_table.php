<?php

declare(strict_types=1);

use App\Models\Negocio;
use App\Models\Sucursal;
use App\Models\Usuario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', static function (Blueprint $table): void {
            $table->id();
            $table->string('correo')->unique();
            $table->string('clave')->unique();
        });

        Schema::create(
            'usuarios_roles',
            static function (Blueprint $table): void {
                $table->id();
                $table->foreignIdFor(Usuario::class);
                $table->enum('rol', ['administrador', 'encargado', 'vendedor']);
            },
        );

        Schema::create(
            'usuarios_establecimientos',
            static function (Blueprint $table): void {
                $table->id();
                $table->foreignIdFor(Usuario::class);
                $table->foreignIdFor(Negocio::class)->nullable();
                $table->foreignIdFor(Sucursal::class)->nullable();
            },
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('usuarios_roles');
        Schema::dropIfExists('usuarios_establecimientos');
    }
};
