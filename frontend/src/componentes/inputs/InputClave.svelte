<script lang="ts">
  import zxcvbn from "zxcvbn";
  import InputFlotante from "@/componentes/inputs/InputFlotante.svelte";
  import { Tooltip } from "bootstrap";
  import { onMount } from "svelte";

  export let name: string;
  export let required: boolean = false;
  export let placeholder: string;
  export let minlength: number | undefined = undefined;
  export let title: string | undefined = undefined;
  export let pattern: string | undefined = undefined;
  export let conBarraFuerza = false;
  export let conMostradorClave = false;

  let type = "password";
  let clave = "";
  let botonVerClave: HTMLButtonElement;
  $: fuerzaClave = zxcvbn(clave).score;
  $: tituloBoton = type === "password" ? "Ver clave" : "Ocultar clave";
  $: botonVerClave && new Tooltip(botonVerClave);

  onMount(() => {
    new Tooltip(botonVerClave);
  });
</script>

<InputFlotante
  bind:type
  {name}
  {required}
  {placeholder}
  {minlength}
  {title}
  {pattern}
  bind:value={clave}
>
  {#if conMostradorClave}
    <button
      bind:this={botonVerClave}
      type="button"
      class="bi fs-4 input-group-text"
      on:click={() => (type = type === "password" ? "text" : "password")}
      class:bi-eye={type === "password"}
      class:bi-eye-slash={type !== "password"}
      data-bs-toggle="tooltip"
      title={tituloBoton}
    >
      <span class="visually-hidden">{tituloBoton}</span>
    </button>
  {/if}
</InputFlotante>
{#if conBarraFuerza}
  <div class="progress">
    <div
      class="progress-bar"
      class:text-bg-success={fuerzaClave >= 3}
      class:text-bg-warning={fuerzaClave === 2}
      class:text-bg-danger={fuerzaClave <= 1}
      style:width="{fuerzaClave * 25}%"
    >
      {#if fuerzaClave <= 1}
        Insegura
      {:else if fuerzaClave === 2}
        Moderada
      {:else if fuerzaClave >= 3}
        Segura
      {/if}
    </div>
  </div>
{/if}
