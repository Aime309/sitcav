$title ??= 'Error 500';
$errorCode = '500';
$errorTitle = 'Error interno del servidor';
$errorText = 'Ocurrio un error inesperado mientras procesabamos tu solicitud. Intenta nuevamente en unos minutos.';

require ROOT_DIR . '/resources/views/layouts/error.php';

