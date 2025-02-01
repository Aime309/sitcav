<script>
  /** @type {keyof typeof import('@fortawesome/fontawesome-free/metadata/icon-families.json')} */
  export let nombre;
  export let style = "";
</script>

<i class="fas fa-{nombre} d-flex justify-content-center {$$props.class}" {style}></i>
