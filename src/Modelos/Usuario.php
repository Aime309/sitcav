<?php

namespace SITCAV\Modelos;

use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leaf\Helpers\Password;
use ZxcvbnPhp\Zxcvbn;

/**
 * @property-read int $id
 * @property int $cedula
 * @property bool $esta_despedido
 * @property string $pregunta_secreta
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
    $validador = new Zxcvbn;
    $fuerzaClave = $validador->passwordStrength($nuevaClave);
    $puntajeFuerzaClave = $fuerzaClave['score'];

    if ($puntajeFuerzaClave < self::PUNTAJE_CLAVE_SEGURA) {
      throw new Error('La nueva clave no es lo suficientemente segura. Debe tener al menos 8 caracteres y contener letras, números y símbolos.');
    }

    $this->clave_encriptada = Password::hash($nuevaClave, PASSWORD_DEFAULT, [
      'cost' => 10,
    ]);

    $this->save();
  }

  function cambiarClave(string $claveAnterior, string $nuevaClave): void
  {
    if (!password_verify($claveAnterior, $this->clave_encriptada)) {
      throw new Error('La clave anterior no es correcta.');
    }

    if (password_verify($nuevaClave, $this->clave_encriptada)) {
      throw new Error('La nueva clave no puede ser igual a la anterior.');
    }

    $validador = new Zxcvbn;
    $fuerzaClave = $validador->passwordStrength($nuevaClave);
    $puntajeFuerzaClave = $fuerzaClave['score'];

    if ($puntajeFuerzaClave < self::PUNTAJE_CLAVE_SEGURA) {
      throw new Error('La nueva clave no es lo suficientemente segura. Debe tener al menos 8 caracteres y contener letras, números y símbolos.');
    }

    $this->clave_encriptada = password_hash($nuevaClave, PASSWORD_DEFAULT, [
      'cost' => 12,
    ]);

    $this->save();
  }

  function cambiarPreguntaYRespuestaSecreta(
    string $pregunta_secreta,
    string $respuesta_secreta,
  ): void {
    $this->pregunta_secreta = $pregunta_secreta;

    $this->respuesta_secreta_encriptada = password_hash(
      $respuesta_secreta,
      PASSWORD_DEFAULT,
      ['cost' => 12]
    );

    $this->save();
  }

  function asegurarValidezRespuestaSecreta(string $respuestaSecreta): void
  {
    if (!Password::verify($respuestaSecreta, $this->respuesta_secreta_encriptada)) {
      throw new Error('La respuesta secreta no es correcta.');
    }
  }

  /**
   * @return BelongsTo<self, self>
   * @deprecated Usa `encargado` en su lugar.
   */
  function encargado(): BelongsTo
  {
    return $this->belongsTo(self::class, 'id_encargado');
  }

  /**
   * @return HasMany<self>
   * @deprecated Usa `empleados` en su lugar.
   */
  function empleados(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(self::class, 'id_encargado');
    }

    return $this->hasMany(self::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Cotizacion>
   */
  function cotizaciones(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(Cotizacion::class, 'id_encargado');
    }

    return $this->hasMany(Cotizacion::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Estado>
   * @deprecated Usa `estados` en su lugar.
   */
  function estados(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(Estado::class, 'id_encargado');
    }

    return $this->hasMany(Estado::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Categoria>
   */
  function categorias(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(Categoria::class, 'id_encargado');
    }

    return $this->hasMany(Categoria::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Marca>
   */
  function marcas(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(Marca::class, 'id_encargado');
    }

    return $this->hasMany(Marca::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<TipoPago>
   * @deprecated Usa `tipos_pago` en su lugar.
   */
  function tipos_pago(): HasMany
  {
    if ($this->esEncargado) {
      return $this->hasMany(TipoPago::class, 'id_encargado');
    }

    return $this->hasMany(TipoPago::class, 'id_encargado', 'id_encargado');
  }

  function getEsEncargadoAttribute(): bool
  {
    return in_array('Encargado', $this->roles);
  }
}
