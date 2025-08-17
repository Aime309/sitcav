<script lang="ts">
  import { v4 } from "uuid";

  export let name: string;
  export let required: boolean = false;
  export let placeholder: string;

  const id = v4();
</script>

<div class="form-floating">
  <select class="form-control" {id} {name} {required}>
    <slot />
  </select>
  <label for={id}>{placeholder}</label>
</div>
