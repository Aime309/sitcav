<script>
  import titulo from "../globales/titulo";
  import { navigate } from "svelte-routing";
  import Swal from "sweetalert2";
  import EncabezadoPublico from "../componentes/EncabezadoPublico.svelte";

  const patronDeClave = "(?=.*\\d)(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\\W).{8,}";
  $titulo = "Registrarse";

  async function manejarEnvio({ target: formulario }) {
    const respuesta = await fetch("./api/registrarse", {
      method: "POST",
      body: new FormData(formulario),
    });

    if (respuesta.ok) {
      navigate("./panel");
    } else {
      Swal.fire({
        title: await respuesta.text(),
        icon: "error",
      });
    }
  }
</script>

<EncabezadoPublico />

<form
  class="col-lg-4 card card-body mx-auto mt-5 row-gap-3"
  on:submit|preventDefault={manejarEnvio}
>
  <input
    class="form-control"
    type="number"
    name="cedula"
    required
    min="1"
    placeholder="Cédula"
  />
  <input
    type="password"
    name="clave"
    required
    minlength="8"
    pattern={patronDeClave}
    title="La contraseña debe tener minimo 8 caracteres, una mayuscula,una minuscula,un simbolo y un digito"
    placeholder="Contraseña"
    class="form-control"
  />

  <input
    class="form-control"
    name="pregunta_secreta"
    required
    placeholder="Pregunta secreta"
  />

  <input
    type="password"
    class="form-control"
    name="respuesta_secreta"
    required
    placeholder="Respuesta secreta"
  />

  <button class="btn btn-primary">Registrarse</button>
</form>
