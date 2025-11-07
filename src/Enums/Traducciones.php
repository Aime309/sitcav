<?php

namespace SITCAV\Enums;

enum Traducciones
{
  case ADVERTENCIAS;
  case SUGERENCIAS;

  function comoArray(): array
  {
    return match ($this) {
      Traducciones::ADVERTENCIAS => [
        'This is a very common password' => 'Esta es una contraseña muy común',
        'This is similar to a commonly used password' => 'Esta es similar a una contraseña comúnmente usada',
        'A word by itself is easy to guess' => 'Una sola palabra es fácil de adivinar',
        'Names and surnames by themselves are easy to guess' => 'Nombres y apellidos por sí solos son fáciles de adivinar',
        'Common names and surnames are easy to guess' => 'Nombres y apellidos comunes son fáciles de adivinar',
        'Sequences like abc or 6543 are easy to guess' => 'Secuencias como abc o 6543 son fáciles de adivinar',
        'This is a top-10 common password' => 'Esta es una de las 10 contraseñas más comunes',
      ],
      Traducciones::SUGERENCIAS => [
        'Add another word or two. Uncommon words are better.' => 'Agrega otra palabra o dos. Las palabras poco comunes son mejores.',
        'Use a longer keyboard pattern with more turns.' => 'Usa un patrón de teclado más largo con más giros.',
        'Avoid repeated words and characters.' => 'Evita palabras y caracteres repetidos.',
        'Avoid sequences' => 'Evita secuencias.',
        'Avoid recent years.' => 'Evita años recientes.',
        'Avoid personal information.' => 'Evita información personal.',
        'Use a few words, avoid common phrases' => 'Usa algunas palabras, evita frases comunes',
        'No need for symbols, digits, or uppercase letters' => 'No es necesario usar símbolos, dígitos o letras mayúsculas',
        'Capitalization doesn\'t help very much' => 'La capitalización no ayuda mucho',
      ],
    };
  }

  function comoString(string $sinTraducir): string
  {
    return $this->comoArray()[$sinTraducir] ?? $sinTraducir;
  }

  function comoObjetoJavaScript(): string
  {
    $objetoJavaScript = '{';

    foreach ($this->comoArray() as $clave => $valor) {
      $claveEscapada = addslashes($clave);
      $valorEscapado = addslashes($this->comoString($valor));

      $objetoJavaScript .= "'$claveEscapada': '$valorEscapado',";
    }

    $objetoJavaScript .= '}';

    return $objetoJavaScript;
  }
}
