<?php

declare(strict_types=1);

use App\Http\Controllers\Ecommerce\{
    CarritoController,
    CerrarSesion as EcommerceCerrarSesion,
    ClienteController as EcommerceClienteController,
    IniciarSesion as EcommerceIniciarSesion,
    NegocioController as EcommerceNegocioController,
    ProductoController as EcommerceProductoController,
    ReservaController as EcommerceReservaController,
};

use App\Http\Controllers\Panel\{
    AdministradorController,
    CerrarSesion,
    ClienteController,
    CompraController,
    EmpleadoController,
    IniciarSesion,
    InventarioController,
    NegocioController,
    PerfilController,
    ProductoController,
    ProveedorController,
    ReservaController,
    SucursalController,
    VentaController,
};

use App\Http\Middleware\Ecommerce\{
    RedirigirUsuariosAutenticados as EcommerceRedirigirUsuariosAutenticados,
    SoloUsuariosAutenticados as EcommerceSoloUsuariosAutenticados
};

use App\Http\Middleware\Panel\{
    RedirigirAlEstablecimientoAsignado,
    RedirigirUsuariosAutenticados,
    SoloAdministradores,
    SoloEncargados,
    SoloEstablecimientosAsignados,
    SoloUsuariosAutenticados
};

use App\Models\Negocio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Route};

/** @deprecated */
define('PDO', DB::getPdo());

Route::fallback(static fn(): View => view('paginas.panel.404'));

Route::redirect('/', '/panel/iniciar-sesion');
Route::redirect('/panel', '/panel/iniciar-sesion');

Route::prefix('panel')
    ->name('panel')
    ->scopeBindings()
    ->group(static function (): void {
        Route::prefix('iniciar-sesion')
            ->name('.iniciar-sesion')
            ->middleware(RedirigirUsuariosAutenticados::class)
            ->group(static function (): void {
                // Ver inicio de sesión del panel
                Route::view('/', 'paginas.panel.iniciar-sesion');

                // Iniciar sesión en el panel
                Route::post('/', IniciarSesion::class);
            });

        Route::prefix('registrarse')
            ->name('.registrarse')
            ->middleware(RedirigirUsuariosAutenticados::class)
            ->controller(AdministradorController::class)
            ->group(static function (): void {
                // Ver registro de administrador del panel
                Route::get('/', 'create');

                // Registrarse como administrador en el panel
                Route::post('/', 'store');
            });

        // Cerrar sesión en el panel
        Route::get('cerrar-sesion', CerrarSesion::class)
            ->name('.cerrar-sesion');

        Route::prefix('negocios')
            ->name('.negocios')
            ->middleware(SoloUsuariosAutenticados::class)
            ->controller(NegocioController::class)
            ->group(static function (): void {
                // Seleccionar establecimiento
                Route::get('/', 'index')
                    ->middleware(RedirigirAlEstablecimientoAsignado::class);

                // Registrar negocio
                Route::post('/', 'store')
                    ->middleware(SoloAdministradores::class);

                Route::prefix('{negocio}')
                    ->name('.{negocio}')
                    ->middleware(SoloEstablecimientosAsignados::class)
                    ->group(static function (): void {
                        // Panel administrativo de un negocio
                        Route::get('/', 'show');

                        // Editar negocio
                        Route::get('editar', 'edit')
                            ->name('.editar')
                            ->middleware(SoloAdministradores::class);

                        // Actualizar negocio
                        Route::post('/', 'update')
                            ->middleware(SoloAdministradores::class);

                        Route::prefix('perfil')
                            ->name('.perfil')
                            ->controller(PerfilController::class)
                            ->group(static function (): void {
                                // Editar perfil
                                Route::get('/', 'edit');

                                // Actualizar perfil
                                Route::post('/', 'update');
                            });

                        Route::prefix('empleados')
                            ->name('.empleados')
                            ->middleware(SoloAdministradores::class)
                            ->controller(EmpleadoController::class)
                            ->group(static function (): void {
                                // Ver empleados
                                Route::get('/', 'index');

                                // Registrar empleado
                                Route::post('/', 'store');

                                // Actualizar empleado
                                Route::post('{empleado}', 'update')
                                    ->name('.{empleado}')
                                    ->withoutScopedBindings(); // TODO: replicar rutas de {negocio} en {sucursal} o redirigir a {sucursal} o extraer {sucursal} y {negocio} a panel
                            });

                        Route::prefix('proveedores')
                            ->name('.proveedores')
                            ->middleware(SoloEncargados::class)
                            ->controller(ProveedorController::class)
                            ->group(static function (): void {
                                // Ver proveedores
                                Route::get('/', 'index');

                                // Registrar proveedor
                                Route::post('/', 'store');

                                Route::prefix('{proveedor}')
                                    ->name('.{proveedor}')
                                    ->group(static function (): void {
                                        Route::get('/', 'edit');

                                        // Actualizar proveedor
                                        Route::post('/', 'update');
                                    });
                            });

                        Route::prefix('clientes')
                            ->name('.clientes')
                            ->middleware(SoloEncargados::class)
                            ->controller(ClienteController::class)
                            ->group(static function (): void {
                                // Ver clientes
                                Route::get('/', 'index');

                                // Registrar cliente
                                Route::post('/', 'store');

                                Route::prefix('{cliente}')
                                    ->name('.{cliente}')
                                    ->group(static function (): void {
                                        Route::get('/', 'edit');

                                        // Actualizar cliente
                                        Route::post('/', 'update');
                                    });
                            });

                        Route::prefix('productos')
                            ->name('.productos')
                            ->controller(ProductoController::class)
                            ->group(static function (): void {
                                // Ver productos
                                Route::get('/', 'index');

                                // Registrar producto
                                Route::post('/', 'store');

                                Route::prefix('{producto}')
                                    ->name('.{producto}')
                                    ->group(static function (): void {
                                        // Editar producto
                                        Route::get('/', 'edit');

                                        // Actualizar producto
                                        Route::post('/', 'update');
                                    });
                            });

                        Route::prefix('inventario')
                            ->name('.inventario')
                            ->middleware(SoloEncargados::class)
                            ->controller(InventarioController::class)
                            ->group(static function (): void {
                                // Ver inventario
                                Route::get('/', 'index');

                                // Actualizar producto en el inventario
                                Route::post('{producto}', 'update')
                                    ->name('.{producto}');
                            });

                        Route::prefix('sucursales')
                            ->name('.sucursales')
                            ->controller(SucursalController::class)
                            ->group(static function (): void {
                                // Ver sucursales
                                Route::get('/', 'index')
                                    ->middleware(SoloAdministradores::class);

                                // Registrar sucursal
                                Route::post('/', 'store')
                                    ->middleware(SoloAdministradores::class);

                                Route::prefix('{sucursal}')
                                    ->name('.{sucursal}')
                                    ->group(static function (): void {
                                        // Panel administrativo de una sucursal
                                        Route::get('/', 'show');

                                        // Editar sucursal
                                        Route::get('editar', 'edit')
                                            ->name('.editar')
                                            ->middleware(SoloAdministradores::class);

                                        // Actualizar sucursal
                                        Route::post('/', 'update')
                                            ->middleware(SoloAdministradores::class);
                                    });
                            });

                        Route::prefix('compras')
                            ->name('.compras')
                            ->middleware(SoloEncargados::class)
                            ->controller(CompraController::class)
                            ->group(static function (): void {
                                // Ver compras
                                Route::get('/', 'index');

                                // Registrar compra
                                Route::post('/', 'store');
                            });

                        Route::prefix('ventas')
                            ->name('.ventas')
                            ->middleware(SoloEncargados::class)
                            ->controller(VentaController::class)
                            ->group(static function (): void {
                                // Ver ventas
                                Route::get('/', 'index');

                                // Registrar venta
                                Route::post('/', 'store');
                            });

                        // Ver reservas
                        Route::get(
                            'reservas',
                            [ReservaController::class, 'index'],
                        )
                            ->name('.reservas')
                            ->middleware(SoloEncargados::class);
                    });
            });
    });

Route::prefix('{negocio}')
    ->name('{negocio}')
    ->group(static function (): void {
        // Ecommerce de un negocio
        Route::get('/', [EcommerceNegocioController::class, 'show']);

        Route::prefix('productos')
            ->name('.productos')
            ->controller(EcommerceProductoController::class)
            ->group(static function (): void {
                // Ver productos de un negocio
                Route::get('/', 'index');

                // Ver producto de un negocio
                Route::get('{producto}', 'show')->name('.{producto}');
            });

        Route::prefix('iniciar-sesion')
            ->name('.iniciar-sesion')
            ->middleware(EcommerceRedirigirUsuariosAutenticados::class)
            ->group(static function (): void {
                // Ver inicio de sesión en un negocio
                Route::get('/', static function (Negocio $negocio): View {
                    return view('paginas.ecommerce.iniciar-sesion', [
                        'negocio' => $negocio,
                    ]);
                });

                // Iniciar sesión en un negocio
                Route::post('/', EcommerceIniciarSesion::class);
            });

        Route::prefix('registrarse')
            ->name('.registrarse')
            ->middleware(EcommerceRedirigirUsuariosAutenticados::class)
            ->controller(EcommerceClienteController::class)
            ->group(static function (): void {
                // Ver registro de cliente en un negocio
                Route::get('/', 'create');

                // Registrarse como cliente en un negocio
                Route::post('/', 'store');
            });

        // Cerrar sesión en un negocio
        Route::get('cerrar-sesion', EcommerceCerrarSesion::class)
            ->name('.cerrar-sesion');

        Route::prefix('perfil')
            ->name('.perfil')
            ->middleware(EcommerceSoloUsuariosAutenticados::class)
            ->controller(EcommerceClienteController::class)
            ->group(static function (): void {
                // Editar perfil en un negocio
                Route::get('/', 'edit');

                // Actualizar perfil en un negocio
                Route::post('/', 'update');
            });

        Route::prefix('carrito')
            ->name('.carrito')
            ->middleware(EcommerceSoloUsuariosAutenticados::class)
            ->controller(CarritoController::class)
            ->group(static function (): void {
                // Ver carrito en un negocio
                Route::get('/', 'index');

                Route::prefix('productos')
                    ->name('.productos')
                    ->group(static function (): void {
                        // Añadir producto al carrito en un negocio
                        Route::post('/', 'update');

                        // Actualizar/Eliminar producto en el carrito en un negocio
                        Route::post('{producto}', 'update')
                            ->name('.{producto}');
                    });
            });

        Route::prefix('reservas')
            ->name('.reservas')
            ->middleware(EcommerceSoloUsuariosAutenticados::class)
            ->controller(EcommerceReservaController::class)
            ->group(static function (): void {
                // Ver reservas en un negocio
                Route::get('/', 'index');

                // Reservar en un negocio
                Route::post('/', 'store');

                Route::prefix('{reserva}')
                    ->name('.{reserva}')
                    ->group(static function (): void {
                        // Ver reserva en un negocio
                        Route::get('/', 'show');

                        // Cancelar reserva en un negocio
                        Route::post('/', 'update');
                    });
            });
    });
