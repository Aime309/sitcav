<script>
  import user from "../stores/user";
  import LoginHeader from "../components/LoginHeader.svelte";
</script>

<LoginHeader />

<h1>Hola usuario#{$user.id}</h1>
<a href="./salir">Salir</a>
