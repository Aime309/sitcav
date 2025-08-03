<?php

namespace SITCAV\Modelos;

use Error;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ZxcvbnPhp\Zxcvbn;

/**
 * @property-read int $id
 * @property int $cedula
 * @property 'Encargado'|'Empleado superior'|'Vendedor' $rol
 * @property bool $esta_despedido
 * @property string $pregunta_secreta
 * @property-read ?self $encargado
 * @property-read Collection<Usuario> $empleados
 * @property-read Collection<Cotizacion> $cotizaciones
 * @property-read Collection<Estado> $estados
 * @property-read Collection<Categoria> $categorias
 * @property-read Collection<Marca> $marcas
 * @property-read Collection<TipoPago> $tipos_pago
 */
class Usuario extends Model
{
  protected $table = 'usuarios';
  public $timestamps = false;
  private const PUNTAJE_CLAVE_SEGURA = 4;

  protected $casts = [
    'esta_activado' => 'boolean',
  ];

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
    return $this->hasMany(self::class, 'id_encargado');
  }

  /**
   * @return HasMany<Cotizacion>
   * @deprecated Usa `encargado` en su lugar.
   */
  function cotizaciones(): HasMany
  {
    if ($this->rol === 'Encargado') {
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
    if ($this->rol === 'Encargado') {
      return $this->hasMany(Estado::class, 'id_encargado');
    }

    return $this->hasMany(Estado::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Categoria>
   * @deprecated Usa `categorias` en su lugar.
   */
  function categorias(): HasMany
  {
    if ($this->rol === 'Encargado') {
      return $this->hasMany(Categoria::class, 'id_encargado');
    }

    return $this->hasMany(Categoria::class, 'id_encargado', 'id_encargado');
  }

  /**
   * @return HasMany<Marca>
   * @deprecated Usa `marcas` en su lugar.
   */
  function marcas(): HasMany
  {
    if ($this->rol === 'Encargado') {
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
    if ($this->rol === 'Encargado') {
      return $this->hasMany(TipoPago::class, 'id_encargado');
    }

    return $this->hasMany(TipoPago::class, 'id_encargado', 'id_encargado');
  }
}
