<script>
  const features = [
    {
      title: "Book An Appointment",
      description:
        "At imperdiet dui accumsan sit amet nulla risus est ultricies quis.",
    },
    {
      title: "Pick up in store",
      description:
        "At imperdiet dui accumsan sit amet nulla risus est ultricies quis.",
    },
    {
      title: "Special packaging",
      description:
        "At imperdiet dui accumsan sit amet nulla risus est ultricies quis.",
    },
    {
      title: "Free global returns",
      description:
        "At imperdiet dui accumsan sit amet nulla risus est ultricies quis.",
    },
  ];
</script>

<section class="features">
  <div class="container-lg">
    <div class="bg-black p-5">
      <div class="row">
        {#each features as feature}
          <div class="col-md-3">
            <h4 class="element-title text-capitalize my-3">{feature.title}</h4>
            <p>{feature.description}</p>
          </div>
        {/each}
      </div>
    </div>
  </div>
</section>
