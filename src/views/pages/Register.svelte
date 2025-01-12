<script>
  import title from "../stores/title";
  import { navigate } from "svelte-routing";
  import Swal from "sweetalert2";
  import LoginHeader from "../components/LoginHeader.svelte";

  const passwordPattern = "(?=.*\\d)(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\\W).{8,}";
  $title = "Registrarse";

  async function handleSubmit({ target }) {
    const response = await fetch("./api/registrarse", {
      method: "POST",
      body: new FormData(target),
    });

    if (response.ok) {
      navigate("./panel");
    } else {
      Swal.fire({
        title: await response.text(),
        icon: "error"
      });
    }
  }
</script>

<LoginHeader />

<form
  class="col-lg-4 card card-body mx-auto mt-5 row-gap-3"
  on:submit|preventDefault={handleSubmit}
>
  <input
    class="form-control"
    type="number"
    name="idCard"
    required
    min="1"
    placeholder="Cédula"
  />
  <input
    type="password"
    name="password"
    required
    minlength="8"
    pattern={passwordPattern}
    title="La contraseña debe tener minimo 8 caracteres, una mayuscula,una minuscula,un simbolo y un digito"
    placeholder="Contraseña"
    class="form-control"
  />

  <input
    class="form-control"
    name="secret_question"
    required
    placeholder="Pregunta secreta"
  />

  <input
    type="password"
    class="form-control"
    name="secret_answer"
    required
    placeholder="Respuesta secreta"
  />

  <button class="btn btn-primary">Registrarse</button>
</form>
