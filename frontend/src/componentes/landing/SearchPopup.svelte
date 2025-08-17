<script>
  const categories = [
    { name: "Jackets", url: "#" },
    { name: "T-shirts", url: "#" },
    { name: "Handbags", url: "#" },
    { name: "Accessories", url: "#" },
    { name: "Cosmetics", url: "#" },
    { name: "Dresses", url: "#" },
    { name: "Jumpsuits", url: "#" },
  ];
</script>

<div class="search-popup">
  <div class="search-popup-container">
    <form>
      <input
        type="search"
        class="form-control border-0 border-bottom bg-dark-subtle"
        placeholder="Type and press enter"
      />
      <button
        type="submit"
        class="border-0 position-absolute bg-white"
        aria-label="Buscar"
        style:top="15px"
        style:right="15px"
      >
        <svg class="search" width="24" height="24">
          <use xlink:href="#search"></use>
        </svg>
      </button>
    </form>

    <h5 class="cat-list-title">Browse Categories</h5>

    <ul class="cat-list">
      {#each categories as category}
        <li class="cat-list-item">
          <a href={category.url} title={category.name}>{category.name}</a>
        </li>
      {/each}
    </ul>
  </div>
</div>
