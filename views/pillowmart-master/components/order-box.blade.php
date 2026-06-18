<div class="order_box">
  <h2>Tu Pedido</h2>
  <ul class="list">
    <li>
      <a href="javascript:">
        Producto
        <span>Total</span>
      </a>
    </li>
    <template x-for="reservedProduct in reservedProducts">
      <li>
        <a href="javascript:">
          <i x-text="reservedProduct.name"></i>
          <span class="middle"
            x-text="`x ${reservedProduct.quantity.toString().padStart(2, 0)}`"></span>
          <span class="last"
            x-text="`$${(reservedProduct.price * reservedProduct.quantity).toFixed(2)}`">
          </span>
        </a>
      </li>
    </template>
  </ul>
  <ul class="list list_2">
    <li>
      <a href="javascript:">
        Total
        <span x-text="`$${reservedProductsTotal.toFixed(2)}`"></span>
      </a>
    </li>
  </ul>
  <div class="creat_account">
    <input id="f-option4" name="selector" type="checkbox" required />
    <label for="f-option4" style="user-select: none">
      He leído y aceptado los
    </label>
    <a href="javascript:">términos & condiciones*</a>
  </div>
  <input type="submit" value="Proceder a Pagar" class="btn_3 w-100" />
</div>
