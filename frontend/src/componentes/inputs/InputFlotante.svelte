<script lang="ts">
  import { v4 } from "uuid";

  export let type: string;
  export let name: string;
  export let required: boolean = false;
  export let placeholder: string;
  export let min: number | undefined = undefined;
  export let value = "";
  export let minlength: number | undefined = undefined;
  export let title: string | undefined = undefined;
  export let pattern: string | undefined = undefined;

  const id = v4();
</script>

<div class="input-group">
  <div class="form-floating">
    <input
      class="form-control"
      placeholder=""
      bind:value
      {id}
      {type}
      {name}
      {required}
      {min}
      {minlength}
      {title}
      {pattern}
    />
    <label for={id}>{placeholder}</label>
  </div>
  <slot />
</div>
