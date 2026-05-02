<?php

declare(strict_types=1);

$registerRecaptchaEnabled = isRecaptchaEnabled();
$registerRecaptchaSiteKey = recaptchaSiteKey();
?>
<!doctype html>
<html lang="es">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>Registro inicial | SITCAV</title>
    <base href="<?= BASE_HREF ?>" />
    <link rel="icon" type="image/png" href="./resources/images/favicon.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <?php if ($registerRecaptchaEnabled): ?>
      <script>
        window.__registerRecaptchaReady = false;
        window.onRegisterRecaptchaLoad = function () {
          window.__registerRecaptchaReady = true;

          if (typeof window.renderRegisterRecaptchaWidgets === "function") {
            window.renderRegisterRecaptchaWidgets();
          }
        };
      </script>
      <script
        src="https://www.google.com/recaptcha/api.js?onload=onRegisterRecaptchaLoad&render=explicit"
        async
        defer></script>
    <?php endif; ?>
    <link rel="stylesheet" href="./resources/css/index.css?id=<?= RESOURCES_ID ?>" />
  </head>

  <body class="register-page">
    <aside class="register-brand" aria-hidden="true">
      <img src="./resources/images/logo-para-correos.jpg" alt="SITCAV" />
    </aside>

    <main class="register-form-panel">
      <div class="register-form-content">
        <h2 class="register-heading">
          <i class="fas fa-user-shield"></i>
          Registro inicial
        </h2>

        <div id="register-error" class="error-message"></div>
        <div id="register-success" class="success-message"></div>

        <form onsubmit="handleAdminRegister(event)">
          <div class="register-form">
            <div class="register-form-grid">
              <div class="register-form-main">
                <div class="form-row">
                  <div class="form-group">
                    <label for="register-names">Nombres <span class="required-asterisk">*</span></label>
                    <input
                      type="text"
                      id="register-names"
                      required
                      placeholder="Ej: Juan José" />
                  </div>
                  <div class="form-group">
                    <label for="register-lastnames">Apellidos <span class="required-asterisk">*</span></label>
                    <input
                      type="text"
                      id="register-lastnames"
                      required
                      placeholder="Ej: Pérez Gómez" />
                  </div>
                </div>

                <div class="form-group">
                  <label for="register-email">Correo Electrónico <span class="required-asterisk">*</span></label>
                  <input
                    type="email"
                    id="register-email"
                    required
                    placeholder="Ej: usuario@empresa.com" />
                </div>

                <div class="form-row">
                  <div class="form-group">
                    <label for="register-password">Contraseña <span class="required-asterisk">*</span></label>
                    <div class="password-wrapper">
                      <input
                        type="password"
                        id="register-password"
                        required
                        minlength="8"
                        title="La contraseña debe tener al menos 8 caracteres, incluir 1 mayúscula, 1 número y 1 símbolo."
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$" />
                      <button
                        type="button"
                        tabindex="-1"
                        class="toggle-password"
                        onclick="togglePassword('register-password', this)">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="register-password-confirm">Confirmar Contraseña <span class="required-asterisk">*</span></label>
                    <div class="password-wrapper">
                      <input
                        type="password"
                        id="register-password-confirm"
                        required
                        minlength="8"
                        pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$" />
                      <button
                        type="button"
                        tabindex="-1"
                        class="toggle-password"
                        onclick="togglePassword('register-password-confirm', this)">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="register-form-side">
                <div class="register-divider">
                  <h4>Pregunta secreta</h4>
                  <p>La usaremos para recuperar tu contraseña si la olvidas.</p>

                  <div class="form-group">
                    <label for="register-secret-question">Pregunta <span class="required-asterisk">*</span></label>
                    <select id="register-secret-question" required>
                      <option value="">Seleccione una pregunta...</option>
                      <option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
                      <option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
                      <option value="¿Cuál es el primer apellido de tu madre?">¿Cuál es el primer apellido de tu madre?</option>
                      <option value="¿Cómo se llamaba tu escuela primaria?">¿Cómo se llamaba tu escuela primaria?</option>
                      <option value="¿Cuál es el nombre de tu mejor amigo de la infancia?">¿Cuál es el nombre de tu mejor amigo de la infancia?</option>
                      <option value="¿Cuál es tu película favorita?">¿Cuál es tu película favorita?</option>
                      <option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
                      <option value="¿Cuál fue tu primer trabajo?">¿Cuál fue tu primer trabajo?</option>
                      <option value="¿Cuál es tu color favorito?">¿Cuál es tu color favorito?</option>
                    </select>
                    <input
                      type="text"
                      id="register-secret-answer"
                      required
                      placeholder="Respuesta"
                      style="margin-top: 5px;" />
                  </div>

                  <?php if ($registerRecaptchaEnabled): ?>
                    <div class="form-group form-group--recaptcha register-actions">
                      <div
                        class="g-recaptcha"
                        data-sitekey="<?= htmlspecialchars($registerRecaptchaSiteKey, ENT_QUOTES, 'UTF-8') ?>"
                        data-register-widget="admin"></div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="form-actions register-actions">
            <a href="dashboard" class="btn btn-outline">
              <i class="fas fa-arrow-left"></i>
              Volver
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-user-shield"></i>
              Crear administrador
            </button>
          </div>
        </form>
      </div>
    </main>

    <script>
      const registerWidgetIds = {};

      function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector("i");

        if (input.type === "password") {
          input.type = "text";
          icon.classList.remove("fa-eye");
          icon.classList.add("fa-eye-slash");
          return;
        }

        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }

      function capitalizeNameValue(value) {
        return value
          .replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, "")
          .toLowerCase()
          .replace(/\s+/g, " ")
          .trimStart()
          .replace(/(^|\s)([a-záéíóúñü])/g, (match, separator, letter) =>
            `${separator}${letter.toUpperCase()}`,
          );
      }

      function bindCapitalizedNameInput(inputId) {
        const input = document.getElementById(inputId);

        if (!input) {
          return;
        }

        input.addEventListener("input", () => {
          input.value = capitalizeNameValue(input.value);
        });
      }

      function validateAdminRegisterFields(errorDiv) {
        const names = document.getElementById("register-names").value.trim();
        const lastnames = document.getElementById("register-lastnames").value.trim();
        const email = document.getElementById("register-email").value.trim();
        const password = document.getElementById("register-password").value;
        const secretQuestion = document.getElementById("register-secret-question").value;
        const secretAnswer = document.getElementById("register-secret-answer").value.trim();
        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
        const namePattern = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?: [A-Za-zÁÉÍÓÚáéíóúÑñ]+)*$/;

        if (!names) {
          errorDiv.textContent = "Debes ingresar los nombres.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!lastnames) {
          errorDiv.textContent = "Debes ingresar los apellidos.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!namePattern.test(names)) {
          errorDiv.textContent =
            "El campo Nombres solo puede contener letras, espacios, la letra ñ y vocales con tilde.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!namePattern.test(lastnames)) {
          errorDiv.textContent =
            "El campo Apellidos solo puede contener letras, espacios, la letra ñ y vocales con tilde.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          errorDiv.textContent = "Debes ingresar un correo electrónico válido.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!passwordPattern.test(password)) {
          errorDiv.textContent =
            "La contraseña debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!secretQuestion) {
          errorDiv.textContent = "Debes seleccionar una pregunta secreta.";
          errorDiv.style.display = "block";
          return false;
        }

        if (!secretAnswer) {
          errorDiv.textContent =
            "Debes ingresar la respuesta de la pregunta secreta.";
          errorDiv.style.display = "block";
          return false;
        }

        return true;
      }

      window.renderRegisterRecaptchaWidgets = function renderRegisterRecaptchaWidgets() {
        if (typeof grecaptcha === "undefined") {
          return;
        }

        document
          .querySelectorAll(".g-recaptcha[data-register-widget]")
          .forEach((container) => {
            if (
              container.dataset.widgetRendered === "true" ||
              !container.dataset.sitekey
            ) {
              return;
            }

            registerWidgetIds[container.dataset.registerWidget] =
              grecaptcha.render(container, {
                sitekey: container.dataset.sitekey,
              });

            container.dataset.widgetRendered = "true";
          });
      };

      function getRegisterRecaptchaToken(widgetName) {
        const widgetId = registerWidgetIds[widgetName];

        if (typeof grecaptcha === "undefined" || widgetId === undefined) {
          return "";
        }

        return grecaptcha.getResponse(widgetId);
      }

      function resetRegisterRecaptcha(widgetName) {
        const widgetId = registerWidgetIds[widgetName];

        if (typeof grecaptcha !== "undefined" && widgetId !== undefined) {
          grecaptcha.reset(widgetId);
        }
      }

      function validateRegisterRecaptcha(errorDiv) {
        const container = document.querySelector(
          ".g-recaptcha[data-register-widget='admin']",
        );

        if (!container) {
          return true;
        }

        if (typeof grecaptcha === "undefined") {
          errorDiv.textContent = "El reCAPTCHA aún se está cargando.";
          errorDiv.style.display = "block";
          return false;
        }

        const token = getRegisterRecaptchaToken("admin");

        if (!token) {
          errorDiv.textContent = "Debes completar el reCAPTCHA.";
          errorDiv.style.display = "block";
          return false;
        }

        return true;
      }

      async function handleAdminRegister(event) {
        event.preventDefault();

        const names = document.getElementById("register-names").value;
        const lastnames = document.getElementById("register-lastnames").value;
        const email = document.getElementById("register-email").value;
        const password = document.getElementById("register-password").value;
        const passwordConfirm = document.getElementById(
          "register-password-confirm",
        ).value;
        const errorDiv = document.getElementById("register-error");
        const successDiv = document.getElementById("register-success");

        errorDiv.style.display = "none";
        successDiv.style.display = "none";

        if (password !== passwordConfirm) {
          errorDiv.textContent = "Las contraseñas no coinciden";
          errorDiv.style.display = "block";
          return;
        }

        if (!validateAdminRegisterFields(errorDiv)) {
          return;
        }

        if (!validateRegisterRecaptcha(errorDiv)) {
          return;
        }

        try {
          const response = await fetch("dashboard/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              names,
              lastnames,
              email,
              contrasena: password,
              secret_question: document.getElementById("register-secret-question")
                .value,
              secret_answer: document.getElementById("register-secret-answer")
                .value,
              recaptcha_token: getRegisterRecaptchaToken("admin"),
            }),
          });

          const responseText = await response.text();
          let data = null;

          try {
            data = responseText ? JSON.parse(responseText) : null;
          } catch (parseError) {
            data = null;
          }

          if (data?.success) {
            successDiv.textContent =
              "Registro exitoso. Redirigiendo al dashboard...";
            successDiv.style.display = "block";
            event.target.reset();
            resetRegisterRecaptcha("admin");

            setTimeout(() => {
              window.location.href = "dashboard";
            }, 2000);

            return;
          }

          errorDiv.textContent =
            data?.message ||
            (response.ok
              ? "No se pudo completar el registro"
              : `El servidor devolvió un error (${response.status}).`);
          errorDiv.style.display = "block";
          resetRegisterRecaptcha("admin");
        } catch (error) {
          errorDiv.textContent = "Error de conexión con el servidor";
          errorDiv.style.display = "block";
          resetRegisterRecaptcha("admin");
        }
      }

      window.addEventListener("DOMContentLoaded", () => {
        bindCapitalizedNameInput("register-names");
        bindCapitalizedNameInput("register-lastnames");
        window.renderRegisterRecaptchaWidgets();
      });
    </script>
  </body>

</html>
