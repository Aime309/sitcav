<script>
  function openNav() {
    document.getElementById("mySidepanel").style.width = "250px";
  }
</script>

<header>
  <!-- header inner -->
  <div class="header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-sm-4">
          <div class="logo">
            <a href="index.html"><img src="images/logo.png" alt="#" /></a>
          </div>
        </div>
        <div class="col-md-8 col-sm-8">
          <div class="right_bottun">
            <ul class="conat_info d_none">
              <li>
                <a href="#"><i class="fa fa-user" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
              </li>
            </ul>
            <button class="openbtn" onclick={openNav}
              ><img src="images/menu_icon.png" alt="#" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
