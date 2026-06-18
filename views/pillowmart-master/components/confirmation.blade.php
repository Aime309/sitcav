<section class="confirmation_part section_padding" x-init="
  reservedProducts = [];

  print();
">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="confirmation_tittle">
          <span>Gracias. Tu pedido ha sido recibido.</span>
        </div>
      </div>
      <div class="col-lg-6 col-lx-4">
        <div class="single_confirmation_details">
          <h4>información del pedido</h4>
          <ul>
            <li>
              <p>número del pedido</p><span>: {{ $reservation[auth()->config('id.key')] }}</span>
            </li>
            <li>
              <p>data</p><span>: {{ date('d/m/Y', strtotime($reservation['created_at'])) }}</span>
            </li>
            <li>
              <p>total</p><span>: USD {{ $reservation['total'] }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="order_details_iner">
          <h3>Detalles del Pedido</h3>
          <table class="table-borderless table">
            <thead>
              <tr>
                <th colspan="2" scope="col">Producto</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($reservation['reservation_details'] as $detail)
                <tr>
                  <th colspan="2">
                    <span>{{ $detail['product']['name'] }}</span>
                  </th>
                  <th>x{{ str_pad($detail['quantity'], 2, 0, STR_PAD_LEFT) }}</th>
                  <th>
                    <span>${{ number_format($detail['quantity'] * $detail['product']['price'], 2) }}</span>
                  </th>
                </tr>
              @endforeach
              <tr>
                <th colspan="3">Subtotal</th>
                <th>
                  <span>${{ number_format($reservation['total'], 2) }}</span>
                </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
