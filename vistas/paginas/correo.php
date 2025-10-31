<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
  <div style="text-align: center; margin-bottom: 20px;">
    <a href="<?= URL_BASE_COMPLETA ?>">
      <img src="https://aimee.42web.io/sitcav/recursos/imagenes/logo-para-correos.jpg" alt="SITCAV Logo" style="max-width: 200px;" />
    </a>
  </div>
  <h2 style="color: #333;">Tu código de verificación es:</h2>
  <p style="font-size: 24px; font-weight: bold; color: #007BFF;"><?= $codigo ?></p>
  <p style="color: #555;">Este código expirará en 1 minuto. Si no solicitaste este código, por favor ignora este correo.</p>
  <form action="<?= URL_BASE_COMPLETA ?>/restablecer-clave/verificar-codigo" method="POST" style="margin-top: 20px;">
    <input type="hidden" name="codigo" value="<?= $codigo ?>" />
    <button type="submit" style="background-color: #007BFF; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Enviar Código</button>
  </form>
  <hr style="margin: 20px 0;" />
  <p style="font-size: 12px; color: #999;">© <?= date('Y') ?> SITCAV. Todos los derechos reservados.</p>
</div>
