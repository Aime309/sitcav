<script>
  import Icono from "./Icono.svelte";
  import {
    Dropdown,
    DropdownToggle,
    DropdownMenu,
    DropdownItem,
  } from "@sveltestrap/sveltestrap";
</script>

<Dropdown class="position-relative">
  <DropdownToggle color="">
    <Icono nombre="bell" tipo="regular" tamaño="2x" />
    <span
      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
    >
      99+
      <span class="visually-hidden">unread messages</span>
    </span>
  </DropdownToggle>
  <DropdownMenu>
    <DropdownItem>Item 1</DropdownItem>
    <DropdownItem>Item 2</DropdownItem>
    <DropdownItem>Item 3</DropdownItem>
  </DropdownMenu>
</Dropdown>
