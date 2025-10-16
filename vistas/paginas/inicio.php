<!--  Row 1 -->
<div class="row">
  <div class="col-lg-8 d-flex align-items-strech">
    <?php Flight::render('componentes/sales-profit') ?>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <div class="col-lg-12">
        <?php Flight::render('componentes/total-followers') ?>
      </div>
      <div class="col-lg-12">
        <?php Flight::render('componentes/total-income') ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-8 d-flex align-items-stretch">
    <?php Flight::render('componentes/popular-products') ?>
  </div>
  <div class="col-lg-4 d-flex align-items-stretch">
    <?php Flight::render('componentes/earning-reports') ?>
  </div>
</div>
<?php Flight::render('componentes/blog-posts') ?>

<script defer src="./recursos/js/dashboard.js"></script>
