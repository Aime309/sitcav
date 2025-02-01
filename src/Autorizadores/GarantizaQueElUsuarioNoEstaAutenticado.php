<?php

namespace SITCAV\Autorizadores;

use Flight;

final readonly class GarantizaQueElUsuarioNoEstaAutenticado
{
  function before()
  {
    if (auth()->id() === null) {
      return true;
    }

    Flight::redirect('/panel');
  }
}
