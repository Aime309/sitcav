var chart_bounce_rate = {
    series: [
      {
        name: "Sales",
        data: [20, 15, 30, 25, 10, 18, 20, 25, 10],
      },
    ],
    chart: {
      fontFamily: "inherit",
      height: 80,
      type: "bar",
      offsetX: -10,
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true,
      },
    },
    colors: ["var(--bs-primary)"],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "55%",
        endingShape: "flat",
        borderRadius: 4,
      },
    },
    tooltip: {
      theme: "dark",
      followCursor: true,
    },
  };
  var chart_line_basic = new ApexCharts(
    document.querySelector("#sales"),
    chart_bounce_rate
  );
  chart_line_basic.render();