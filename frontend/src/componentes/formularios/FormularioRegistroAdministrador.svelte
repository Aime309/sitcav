<script lang="ts">
  import InputClave from "@/componentes/inputs/InputClave.svelte";
  import InputFlotante from "@/componentes/inputs/InputFlotante.svelte";
  import SelectFlotante from "@/componentes/inputs/SelectFlotante.svelte";
  import type { EventHandler } from "svelte/elements";
  import Toast from "@/componentes/Toast.svelte";

  let toast: Toast;

  const manejarEnvio: EventHandler<SubmitEvent, HTMLFormElement> = ({
    currentTarget: formulario,
  }) => {
    fetch("./api/crear-cuenta", {
      method: "post",
      body: JSON.stringify({
        cedula: formulario.cedula.value,
        clave: formulario.clave.value,
        pregunta_secreta: formulario.pregunta_secreta.value,
        respuesta_secreta: formulario.respuesta_secreta.value,
      }),
      headers: {
        "content-type": "application/json",
      },
    }).then((respuesta) => {
      if (respuesta.status === 200) {
        return (location.href = "./administracion");
      }

      toast.mostrar({
        tipo: "error",
        mensaje: "Error al crear cuenta. Por favor, verifica tus datos.",
      });
    });
  };
</script>

<Toast bind:this={toast} />

<form
  method="post"
  class="d-grid gap-3"
  on:submit|preventDefault={manejarEnvio}
>
  <InputFlotante
    type="number"
    name="cedula"
    required
    min={1}
    placeholder="Cédula"
  />
  <InputClave
    name="clave"
    required
    minlength={8}
    placeholder="Clave"
    title="Debe tener al menos 8 caracteres, 1 mayúscula, 1 número y 1 símbolo (@, $, !, %, *, ?, &)"
    pattern={"(?=.*[a-záéíóúñ])(?=.*[A-ZÁÉÍÓÚÑ])(?=.*\\d)(?=.*[@$!%*?&.])[A-ZÁÉÍÓÚÑa-záéíóúÑ\\d@$!%*?&.]{8,}"}
    conBarraFuerza
    conMostradorClave
  />
  <SelectFlotante
    name="pregunta_secreta"
    required
    placeholder="Pregunta secreta"
  >
    <option>¿Cuál es el nombre de tu mascota?</option>
    <option>¿Quién fué tu primer amor?</option>
    <option>¿Quién es tu mejor amigo?</option>
    <option>¿Cuál es tu ciudad natal?</option>
    <option>¿Cómo se llama la escuela primaria donde estudiaste?</option>
    <option>¿Cuál es tu película favorita?</option>
    <option>¿Cuál es tu libro favorito?</option>
    <option>¿Cuál es tu canción favorita?</option>
    <option>¿Cuál es tu comida favorita?</option>
    <option>¿Cuál es tu deporte favorito?</option>
  </SelectFlotante>
  <InputClave
    name="respuesta_secreta"
    required
    minlength={3}
    placeholder="Respuesta secreta"
    conMostradorClave
  />
  <button class="btn btn-primary">Crear cuenta</button>
  <div class="text-center">
    ¿Ya tienes cuenta? <a href="./ingresar">Ingresa</a>
  </div>
</form>
