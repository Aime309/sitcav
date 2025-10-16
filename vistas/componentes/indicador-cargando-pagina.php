<div
  class="loader loader-bouncing"
  :class="active && 'is-active'"
  x-data="{ active: true, }"
  x-init="document.addEventListener('DOMContentLoaded', () => (this.active = false))">
</div>
