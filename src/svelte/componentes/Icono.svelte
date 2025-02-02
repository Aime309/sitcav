<script context="module" lang="ts">
  export type Icono =
    keyof typeof import("@fortawesome/fontawesome-free/metadata/icon-families.json");
</script>

<script lang="ts">
  import "@fortawesome/fontawesome-free/css/all.min.css";

  export let nombre: Icono;
  export let tamaño:
    | "2xs"
    | "xs"
    | "sm"
    | "lg"
    | "xl"
    | "2xl"
    | "1x"
    | "2x"
    | "3x"
    | "4x"
    | "5x"
    | "6x"
    | "7x"
    | "8x"
    | "9x"
    | "10x" = "1x";
  export let style: undefined | string = undefined;
  export let tamañoFijo = false;
  export let rotar:
    | undefined
    | 90
    | 180
    | 270
    | "horizontal"
    | "vertical"
    | "both" = undefined;
  export let animacion:
    | undefined
    | "beat"
    | "fade"
    | "beat-fade"
    | "bounce"
    | "flip"
    | "shake"
    | "spin"
    | "spin-pulse"
    | "spin-reverse" = undefined;
</script>

<i
  class:fa-fw={tamañoFijo}
  class="fas fa-{nombre} fa-{tamaño} {rotar
    ? `fa-rotate-${rotar}`
    : ''} {animacion ? `fa-${animacion}` : ''} {$$props.class}"
  {style}
></i>
