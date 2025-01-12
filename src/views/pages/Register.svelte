<script>
  import title from "../stores/title";
  import { navigate } from "svelte-routing";

  const passwordPattern = "(?=.*\\d)(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\\W).{8,}";

  $title = "Registrarse";

  function manejarenvio(evento) {
    fetch("./api/registrarse", {
      method: "POST",
      body: new FormData(evento.target),
    })
      .then((respuesta) => respuesta.json())
      .then((usuario) => {
        navigate("./panel");
      });
  }
</script>

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css"
/>

<form on:submit|preventDefault={manejarenvio}>
  <input type="number" name="idCard" required min="1" placeholder="Cédula" />
  <input
    type="password"
    name="password"
    required
    minlength="8"
    pattern={passwordPattern}
    title="La contraseña debe tener minimo 8 caracteres, una mayuscula,una minuscula,un simbolo y un digito"
    placeholder="Contraseña"
  />

  <input name="secret_question" required placeholder="Pregunta secreta" />

  <input
    type="password"
    name="secret_answer"
    required
    placeholder="Respuesta secreta"
  />

  <button type="submit">Registrarse</button>
</form>
