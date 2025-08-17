<script>
  const products = [
    {
      id: 1,
      name: "22 Inch Monitor",
      description: "Brief description",
      price: 12,
    },
    {
      id: 2,
      name: "Mobile phone",
      description: "Brief description",
      price: 8,
    },
    {
      id: 3,
      name: "Drone",
      description: "Brief description",
      price: 5,
    },
  ];
</script>

<div
  class="offcanvas offcanvas-end"
  data-bs-scroll="true"
  tabindex="-1"
  id="offcanvasCart"
>
  <div class="offcanvas-header justify-content-center">
    <button
      type="button"
      class="btn-close"
      data-bs-dismiss="offcanvas"
      aria-label="Close"
    >
    </button>
  </div>
  <div class="offcanvas-body">
    <div class="order-md-last">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-primary">Your cart</span>
        <span class="badge bg-primary rounded-pill">{products.length}</span>
      </h4>
      <ul class="list-group mb-3">
        {#each products as product (product.id)}
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">{product.name}</h6>
              <small class="text-body-secondary">{product.description}</small>
            </div>
            <span class="text-body-secondary">${product.price}</span>
          </li>
        {/each}
        <li class="list-group-item d-flex justify-content-between">
          <span>Total (USD)</span>
          <strong>
            ${products.reduce((total, product) => total + product.price, 0)}
          </strong>
        </li>
      </ul>

      <button class="w-100 btn btn-primary btn-lg">
        Continue to Checkout
      </button>
    </div>
  </div>
</div>
