<?php

declare(strict_types=1);

use App\Http\Controllers\Ecommerce\CarritoController;
use App\Http\Controllers\Ecommerce\CerrarSesion as EcommerceCerrarSesion;
use App\Http\Controllers\Ecommerce\ClienteController as EcommerceClienteController;
use App\Http\Controllers\Ecommerce\IniciarSesion as EcommerceIniciarSesion;
use App\Http\Controllers\Ecommerce\NegocioController as EcommerceNegocioController;
use App\Http\Controllers\Ecommerce\ProductoController as EcommerceProductoController;
use App\Http\Controllers\Ecommerce\ReservaController as EcommerceReservaController;
use App\Http\Controllers\Panel\AdministradorController;
use App\Http\Controllers\Panel\CerrarSesion;
use App\Http\Controllers\Panel\ClienteController;
use App\Http\Controllers\Panel\CompraController;
use App\Http\Controllers\Panel\EmpleadoController;
use App\Http\Controllers\Panel\IniciarSesion;
use App\Http\Controllers\Panel\InventarioController;
use App\Http\Controllers\Panel\NegocioController;
use App\Http\Controllers\Panel\PerfilController;
use App\Http\Controllers\Panel\ProductoController;
use App\Http\Controllers\Panel\ProveedorController;
use App\Http\Controllers\Panel\ReservaController;
use App\Http\Controllers\Panel\SucursalController;
use App\Http\Controllers\Panel\VentaController;
use App\Models\Negocio;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/** @deprecated */
define('PDO', DB::getPdo());

Route::redirect('/', 'panel/iniciar-sesion');

Route::prefix('panel')->group(static function (): void {
    Route::prefix('iniciar-sesion')->group(static function (): void {
        // Ver inicio de sesión del panel
        Route::view('/', 'panel_iniciar-sesion')
            ->name('panel.iniciar-sesion');

        // Iniciar sesión en el panel
        Route::post('/', IniciarSesion::class);
    });

    Route::prefix('registrarse')->group(static function (): void {
        // Ver registro de administrador del panel
        Route::get('/', [AdministradorController::class, 'create'])
            ->name('panel.registrarse');

        // Registrarse como administrador en el panel
        Route::post('/', [AdministradorController::class, 'store']);
    });

    // Cerrar sesión en el panel
    Route::get('cerrar-sesion', CerrarSesion::class)
        ->name('panel.cerrar-sesion');

    Route::prefix('negocios')->group(static function (): void {
        // Seleccionar establecimiento
        Route::get('/', [NegocioController::class, 'index'])
            ->name('panel.negocios');

        // Registrar negocio
        Route::post('/', [NegocioController::class, 'store']);

        Route::prefix('{negocio}')->group(static function (): void {
            // Panel administrativo de un negocio
            Route::get('/', [NegocioController::class, 'show'])
                ->name('panel.negocios.{negocio}');

            // Editar negocio
            Route::get('editar', [NegocioController::class, 'edit'])
                ->name('panel.negocios.{negocio}.editar');

            // Actualizar negocio
            Route::post('/', [NegocioController::class, 'update']);

            Route::prefix('perfil')->group(static function (): void {
                // Editar perfil
                Route::get('/', [PerfilController::class, 'edit'])
                    ->name('panel.negocios.{negocio}.perfil');

                // Actualizar perfil
                Route::post('/', [PerfilController::class, 'update']);
            });

            Route::prefix('empleados')->group(static function (): void {
                // Ver empleados
                Route::get('/', [EmpleadoController::class, 'index'])
                    ->name('panel.negocios.{negocio}.empleados');

                // Registrar empleado
                Route::post('/', [EmpleadoController::class, 'store']);

                // Actualizar empleado
                Route::post(
                    '{empleado}',
                    [EmpleadoController::class, 'update'],
                )
                    ->name('panel.negocios.{negocio}.empleados.{empleado}');
            });

            Route::prefix('proveedores')->group(static function (): void {
                // Ver proveedores
                Route::get('/', [ProveedorController::class, 'index'])
                    ->name('panel.negocios.{negocio}.proveedores');

                // Registrar proveedor
                Route::post('/', [ProveedorController::class, 'store']);

                // Actualizar proveedor
                Route::post(
                    '{proveedor}',
                    [ProveedorController::class, 'update'],
                );
            });

            Route::prefix('clientes')->group(static function (): void {
                // Ver clientes
                Route::get('/', [ClienteController::class, 'index'])
                    ->name('panel.negocios.{negocio}.clientes');

                // Registrar cliente
                Route::post('/', [ClienteController::class, 'store']);

                // Actualizar cliente
                Route::post(
                    '{cliente}',
                    [ClienteController::class, 'update'],
                );
            });

            Route::prefix('productos')->group(static function (): void {
                // Ver productos
                Route::get('/', [ProductoController::class, 'index'])
                    ->name('panel.negocios.{negocio}.productos');

                // Registrar producto
                Route::post('/', [ProductoController::class, 'store']);

                Route::prefix('{producto}')->group(static function (): void {
                    // Editar producto
                    Route::get('/', [ProductoController::class, 'edit'])
                        ->name('panel.negocios.{negocio}.productos.{producto}');

                    // Actualizar producto
                    Route::post('/', [ProductoController::class, 'update']);
                });
            });

            Route::prefix('inventario')->group(static function (): void {
                // Ver inventario
                Route::get('/', [InventarioController::class, 'index'])
                    ->name('panel.negocios.{negocio}.inventario');

                // Actualizar producto en el inventario
                Route::post(
                    '{producto}',
                    [InventarioController::class, 'update'],
                )
                    ->name('panel.negocios.{negocio}.inventario.{producto}');
            });

            Route::prefix('sucursales')->group(static function (): void {
                // Ver sucursales
                Route::get('/', [SucursalController::class, 'index'])
                    ->name('panel.negocios.{negocio}.sucursales');

                // Registrar sucursal
                Route::post('/', [SucursalController::class, 'store']);

                Route::prefix('{sucursal}')->group(static function (): void {
                    // Panel administrativo de una sucursal
                    Route::get('/', [SucursalController::class, 'show'])
                        ->name('panel.negocios.{negocio}.sucursales.{sucursal}');

                    // Editar sucursal
                    Route::get('editar', [SucursalController::class, 'edit'])
                        ->name('panel.negocios.{negocio}.sucursales.{sucursal}.editar');

                    // Actualizar sucursal
                    Route::post('/', [SucursalController::class, 'update']);
                });
            });

            Route::prefix('compras')->group(static function (): void {
                // Ver compras
                Route::get('/', [CompraController::class, 'index'])
                    ->name('panel.negocios.{negocio}.compras');

                // Registrar compra
                Route::post('/', [CompraController::class, 'store']);
            });

            Route::prefix('ventas')->group(static function (): void {
                // Ver ventas
                Route::get('/', [VentaController::class, 'index'])
                    ->name('panel.negocios.{negocio}.ventas');

                // Registrar venta
                Route::post('/', [VentaController::class, 'store']);
            });

            // Ver reservas
            Route::get('reservas', [ReservaController::class, 'index'])
                ->name('panel.negocios.{negocio}.reservas');
        });
    });
});

Route::prefix('{negocio}')->group(static function (): void {
    // Ecommerce de un negocio
    Route::get('/', [EcommerceNegocioController::class, 'show'])
        ->name('{negocio}');

    Route::prefix('productos')->group(static function (): void {
        // Ver productos de un negocio
        Route::get('/', [EcommerceProductoController::class, 'index'])
            ->name('{negocio}.productos');

        // Ver producto de un negocio
        Route::get('{producto}', [EcommerceProductoController::class, 'show'])
            ->name('{negocio}.productos.{producto}');
    });

    Route::prefix('iniciar-sesion')->group(static function (): void {
        // Ver inicio de sesión en un negocio
        Route::get(
            '/',
            static function (Request $request, Negocio $negocio): View {
                return view('{negocio}_iniciar-sesion', [
                    'negocio' => $negocio,
                ]);
            },
        )->name('{negocio}.iniciar-sesion');

        // Iniciar sesión en un negocio
        Route::post('/', EcommerceIniciarSesion::class);
    });

    Route::prefix('registrarse')->group(static function (): void {
        // Ver registro de cliente en un negocio
        Route::get('/', [EcommerceClienteController::class, 'create'])
            ->name('{negocio}.registrarse');

        // Registrarse como cliente en un negocio
        Route::post('/', [EcommerceClienteController::class, 'store']);
    });

    // Cerrar sesión en un negocio
    Route::get('cerrar-sesion', EcommerceCerrarSesion::class)
        ->name('{negocio}.cerrar-sesion');

    Route::prefix('perfil')->group(static function (): void {
        // Editar perfil en un negocio
        Route::get('/', [EcommerceClienteController::class, 'edit'])
            ->name('{negocio}.perfil');

        // Actualizar perfil en un negocio
        Route::post('/', [EcommerceClienteController::class, 'update']);
    });

    Route::prefix('carrito')->group(static function (): void {
        // Ver carrito en un negocio
        Route::get('/', [CarritoController::class, 'index'])
            ->name('{negocio}.carrito');

        Route::prefix('productos')->group(static function (): void {
            // Añadir producto al carrito en un negocio
            Route::post('/', [CarritoController::class, 'update'])
                ->name('{negocio}.carrito.productos');

            Route::prefix('{producto}')->group(static function (): void {
                // Actualizar/Eliminar producto en el carrito en un negocio
                Route::post('/', [CarritoController::class, 'update'])
                    ->name('{negocio}.carrito.productos.{producto}');
            });
        });
    });

    Route::prefix('reservas')->group(static function (): void {
        // Ver reservas en un negocio
        Route::get('/', [EcommerceReservaController::class, 'index'])
            ->name('{negocio}.reservas');

        // Reservar en un negocio
        Route::post('/', [EcommerceReservaController::class, 'store']);

        Route::prefix('{reserva}')->group(static function (): void {
            // Ver reserva en un negocio
            Route::get('/', [EcommerceReservaController::class, 'show'])
                ->name('{negocio}.reservas.{reserva}');

            // Cancelar reserva en un negocio
            Route::post('/', [EcommerceReservaController::class, 'update']);
        });
    });
});
