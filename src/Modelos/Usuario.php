<?php

namespace SITCAV\Modelos;

use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leaf\Helpers\Password;
use SITCAV\Enums\Traducciones;
use ZxcvbnPhp\Zxcvbn;

/**
 * @property-read int $id
 * @property string $email
 * @property int $cedula
 * @property-read string[] $roles
 * @property bool $esta_despedido
 * @property string $pregunta_secreta
 * @property ?string $url_imagen
 * @property-read ?self $encargado
 * @property-read Collection<Usuario> $empleados
 * @property-read Collection<Cotizacion> $cotizaciones
 * @property-read Collection<Estado> $estados
 * @property-read Collection<Categoria> $categorias
 * @property-read Collection<Marca> $marcas
 * @property-read Collection<TipoPago> $tipos_pago
 * @property-read bool $esEncargado
 */
class Usuario extends Model
{
  protected $table = 'usuarios';
  public $timestamps = false;
  private const PUNTAJE_CLAVE_SEGURA = 2;

  protected $casts = [
    'esta_despedido' => 'boolean',
    'roles' => 'array',
  ];

  protected $hidden = [
    'id_encargado',
    'clave_encriptada',
    'respuesta_secreta_encriptada',
  ];

  function restablecerClave(string $nuevaClave): void
  {
    $this->asegurarQueLaClaveEsSegura($nuevaClave);

    $this->clave_encriptada = Password::hash($nuevaClave, Password::DEFAULT, [
      'cost' => 10,
    ]);

    $this->save();
  }

  function cambiarClave(string $claveAnterior, string $nuevaClave): void
  {
    if (!Password::verify($claveAnterior, $this->clave_encriptada)) {
      throw new Error('La clave anterior no es correcta.');
    }

    if (Password::verify($nuevaClave, $this->clave_encriptada)) {
      throw new Error('La nueva clave no puede ser igual a la anterior.');
    }

    $this->asegurarQueLaClaveEsSegura($nuevaClave);

    $this->clave_encriptada = Password::hash($nuevaClave, Password::DEFAULT, [
      'cost' => 10,
    ]);

    $this->save();
  }

  private function asegurarQueLaClaveEsSegura(string $clave): void
  {
    $traducciones = Traducciones::class;
    $validador = new Zxcvbn;
    $fuerzaClave = $validador->passwordStrength($clave);
    $puntajeFuerzaClave = $fuerzaClave['score'];

    $advertencia = $traducciones::ADVERTENCIAS->comoString($fuerzaClave['feedback']['warning'] ?? '');

    $sugerencias = join('', array_map(
      static fn(string $sugerencia): string => "<li>&bullet; {$traducciones::SUGERENCIAS->comoString($sugerencia)}</li>",
      $fuerzaClave['feedback']['suggestions'] ?? ['Debe tener al menos 8 caracteres y contener letras, números y símbolos.'],
    ));

    if ($puntajeFuerzaClave < self::PUNTAJE_CLAVE_SEGURA) {
      throw new Error(<<<html
        La nueva clave no es lo suficientemente segura. <strong>$advertencia</strong>
        <ul>$sugerencias</ul>
      html);
    }
  }

  function cambiarPreguntaYRespuestaSecreta(
    string $pregunta_secreta,
    string $respuesta_secreta,
  ): void {
    $this->pregunta_secreta = $pregunta_secreta;

    $this->respuesta_secreta_encriptada = Password::hash(
      $respuesta_secreta,
      Password::DEFAULT,
      ['cost' => 10]
    );

    $this->save();
  }

  function asegurarValidezRespuestaSecreta(string $respuestaSecreta): void
  {
    if (!Password::verify($respuestaSecreta, $this->respuesta_secreta_encriptada)) {
      throw new Error('La respuesta secreta no es correcta.');
    }
  }

  /** @return BelongsTo<self, self> */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(self::class, 'id_encargado');
  }

  /** @return HasMany<self> */
  function empleados(): HasMany
  {
    return $this->encargado?->hasMany(self::class, 'id_encargado') ?? $this->hasMany(self::class, 'id_encargado');
  }

  /** @return HasMany<Cotizacion> */
  function cotizaciones(): HasMany
  {
    return $this->encargado?->hasMany(Cotizacion::class, 'id_encargado') ?? $this->hasMany(Cotizacion::class, 'id_encargado');
  }

  /** @return HasMany<Estado> */
  function estados(): HasMany
  {
    return $this->encargado?->hasMany(Estado::class, 'id_encargado') ?? $this->hasMany(Estado::class, 'id_encargado');
  }

  /** @return HasMany<Categoria> */
  function categorias(): HasMany
  {
    return $this->encargado?->hasMany(Categoria::class, 'id_encargado') ?? $this->hasMany(Categoria::class, 'id_encargado');
  }

  /** @return HasMany<Marca> */
  function marcas(): HasMany
  {
    return $this->encargado?->hasMany(Marca::class, 'id_encargado') ?? $this->hasMany(Marca::class, 'id_encargado');
  }

  /** @return HasMany<TipoPago> */
  function tipos_pago(): HasMany
  {
    return $this->encargado?->hasMany(TipoPago::class, 'id_encargado') ?? $this->hasMany(TipoPago::class, 'id_encargado');
  }

  function getEsEncargadoAttribute(): bool
  {
    return in_array('Encargado', $this->roles);
  }
}
