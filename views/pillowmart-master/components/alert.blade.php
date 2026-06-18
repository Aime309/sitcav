<div
  class="alert alert-{{ $type ?? "warning" }} alert-dismissible fade show"
  role="alert"
>
  {!! $slot !!}
  <button
    aria-label="Close"
    class="close"
    data-dismiss="alert"
    type="button"
  >
    <span aria-hidden="true">&times;</span>
  </button>
</div>
