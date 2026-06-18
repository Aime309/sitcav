<h3>Detalles de Facturación</h3>

@if (is_array($message))
<x-alert type="danger">
  <ul>
    @foreach ($message as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</x-alert>
@endif

<div class="row contact_form">
  <div class="col-md-6 form-group p_star">
    <input class="form-control" id="first" name="user[name]"
      placeholder="Primer nombre *" value="{{ auth()->user()->name }}" required />
  </div>
  <div class="col-md-6 form-group p_star">
    <input class="form-control" id="last" name="user[last_name]"
      placeholder="Primer apellido *" value="{{ auth()->user()->last_name }}" required />
  </div>
  <div class="col-md-6 form-group p_star">
    <input class="form-control" id="number" name="user[phone]"
      placeholder="Número de teléfono" type="tel"
      value="{{ auth()->user()->phone }}" required />
  </div>
  <div class="col-md-6 form-group p_star">
    <input class="form-control" id="email" name="user[email]"
      placeholder="Dirección de Correo Electrónico" type="email"
      value="{{ auth()->user()->email }}" required />
  </div>
  <div class="col-md-12 form-group p_star">
    <input class="form-control" id="add1" name="user[location]" placeholder="Dirección"
      value="{{ auth()->user()->location }}" required />
  </div>
</div>
