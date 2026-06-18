<section class="checkout_area section_padding">
  <div class="container">
    <form action="{{ Flight::getUrl('ecommerce.reservations') }}" method="post" class="billing_details">
      <template x-for="(reservedProduct, index) in reservedProducts">
        <div style="display: contents">
          <input type="hidden" x-bind:name="`reservedProducts[${index}][{{ auth()->config('id.key') }}]`"
            x-bind:value="reservedProduct.{{ auth()->config('id.key') }}" />
          <input type="hidden" x-bind:name="`reservedProducts[${index}][quantity]`"
            x-bind:value="reservedProduct.quantity" />
        </div>
      </template>

      <div class="row">
        <div class="col-lg-8">
          <x-checkout-details />
        </div>
        <div class="col-lg-4">
          <x-order-box />
        </div>
      </div>
    </form>
  </div>
</section>
