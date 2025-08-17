<script lang="ts">
  import Toast from "@/componentes/Toast.svelte";
  import type { EventHandler } from "svelte/elements";
  import InputFlotante from "@/componentes/inputs/InputFlotante.svelte";
  import InputClave from "@/componentes/inputs/InputClave.svelte";

  let toast: Toast;

  const manejarEnvio: EventHandler<SubmitEvent, HTMLFormElement> = ({
    currentTarget: formulario,
  }) => {
    fetch("./api/ingresar", {
      method: "post",
      body: JSON.stringify({
        cedula: formulario.cedula.value,
        clave: formulario.clave.value,
      }),
      headers: {
        "content-type": "application/json",
      },
    }).then((respuesta) => {
      if (respuesta.status === 200) {
        return (location.href = "./administracion");
      }

      toast.mostrar({ tipo: "error", mensaje: "Cédula o clave incorrecta" });
    });
  };
</script>

<Toast bind:this={toast} />

<form on:submit|preventDefault={manejarEnvio} class="d-grid gap-3">
  <InputFlotante type="number" name="cedula" required placeholder="Cédula" />
  <InputClave name="clave" required placeholder="Clave" conMostradorClave />
  <div class="text-end">
    <a href="./restablecer-clave"> ¿Has olvidado tu contraseña? </a>
  </div>
  <button class="btn btn-primary">Ingresar</button>
  <div class="text-center">
    <span>¿Nuevo en SITCAV?</span>
    <a href="./crear-cuenta">Crea una cuenta</a>
  </div>
</form>
