<div
  class="loader loader-bouncing"
  x-data="{ active: true, }"
  x-init="setTimeout(() => { active = false }, 500)"
  :class="active && 'is-active'">
</div>
