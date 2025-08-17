<script lang="ts">
  import { Toast } from "bootstrap";
  import { onMount } from "svelte";

  let elemento: HTMLDivElement;
  let toast: Toast;

  let clase: "exito" | "error";
  let texto: string;

  export const mostrar = ({
    tipo,
    mensaje,
  }: {
    tipo: typeof clase;
    mensaje: typeof texto;
  }) => {
    clase = tipo;
    texto = mensaje;
    toast.show();
  };

  onMount(() => {
    toast = new Toast(elemento);
  });
</script>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div class="toast" bind:this={elemento}>
    <div
      class="toast-header"
      class:text-bg-success={clase === "exito"}
      class:text-bg-danger={clase === "error"}
    >
      <i
        class="bi me-2"
        class:bi-check-circle={clase === "exito"}
        class:bi-x-circle={clase === "error"}
      ></i>
      <strong class="me-auto">{texto}</strong>
      <button class="btn-close" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
