<script>
  import "bootstrap/dist/css/bootstrap.min.css";
  import "@fortawesome/fontawesome-free/css/all.min.css";
  import "@fontsource/poppins";
  import "../styles/style.css";
  import "../styles/responsive.css";
  import "../scripts/custom";
  import LoginSidebar from "../components/LoginSidebar.svelte";
  import LoginHeader from "../components/LoginHeader.svelte";
  import LoginBanner from "../components/LoginBanner.svelte";
  import LoginAbout from "../components/LoginAbout.svelte";
  import LoginChoose from "../components/LoginChoose.svelte";
  import LoginContact from "../components/LoginContact.svelte";
  import LoginFooter from "../components/LoginFooter.svelte";
  import LoginService from "../components/LoginService.svelte";
  import LoginTestimonials from "../components/LoginTestimonials.svelte";
  import LoginVehicles from "../components/LoginVehicles.svelte";
</script>

<LoginSidebar />
<LoginHeader />
<LoginBanner />
<LoginAbout />
<LoginService />
<LoginVehicles />
<LoginTestimonials />
<LoginChoose />
<LoginContact />
<LoginFooter />
