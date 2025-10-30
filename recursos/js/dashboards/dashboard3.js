document.addEventListener("DOMContentLoaded", function () {
  var investments = {
    series: [
      {
        name: "BTC",
        data: [3500, 2500, 4000, 2500, 5500, 3500, 2500],
      },
      {
        name: "ETH",
        data: [3000, 1500, 3100, 5000, 3000, 5500, 3500],
      },
    ],
    chart: {
      fontFamily: "inherit",
      foreColor: "#adb0bb",
      height: 220,
      type: "line",
      toolbar: {
        show: false,
      },
    },
    legend: {
      show: false,
    },
    stroke: {
      width: 3,
      curve: "smooth",
    },
    grid: {
      show: false,
      strokeDashArray: 3,
      borderColor: "#90A4AE50",
    },
    colors: ["var(--bs-primary)", "var(--bs-gray-300)"],
    markers: {
      size: 0,
    },
    yaxis: {
      show: false,
    },
    xaxis: {
      type: "category",
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July"],
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    tooltip: {
      theme: "dark",
    },
  };
  new ApexCharts(document.querySelector("#investments"), investments).render();

  // =====================================
  // Sales Profit Start
  // =====================================

  var options = {
    series: [
      {
        type: "area",
        name: "This Year",
        data: [
          {
            x: "Aug",
            y: 25,
          },
          {
            x: "Sep",
            y: 13,
          },
          {
            x: "Oct",
            y: 20,
          },
          {
            x: "Nov",
            y: 40,
          },
          {
            x: "Dec",
            y: 45,
          },
          {
            x: "Jan",
            y: 50,
          },
          {
            x: "Feb",
            y: 70,
          },
          {
            x: "Mar",
            y: 30,
          },
        ],
      },
      {
        type: "line",
        name: "Last Year",
        chart: {
          foreColor: "#adb0bb",
          dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 5,
            left: 0,
            blur: 3,
            color: "#000",
            opacity: 0.1,
          },
        },
        data: [
          {
            x: "Aug",
            y: 50,
          },
          {
            x: "Sep",
            y: 35,
          },
          {
            x: "Oct",
            y: 30,
          },
          {
            x: "Nov",
            y: 20,
          },
          {
            x: "Dec",
            y: 20,
          },
          {
            x: "Jan",
            y: 30,
          },
          {
            x: "Feb",
            y: 35,
          },
          {
            x: "Mar",
            y: 40,
          },
        ],
      },
    ],
    chart: {
      height: 210,
      fontFamily: "inherit",
      foreColor: "#adb0bb",
      offsetX:-15,
      animations: {
        speed: 500,
      },
      toolbar: {
        show: false,
      },
    },
    colors: ["var(--bs-primary)", "rgba(119, 119, 142, 0.05)"],
    dataLabels: {
      enabled: false,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0.1,
        opacityTo: 0,
        stops: [100],
      },
    },
    grid: {
      borderColor: "var(--bs-border-color)",
    },
    stroke: {
      curve: "smooth",
      width: 2,
    },
    xaxis: {
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    legend: {
      show: false,
    },
    tooltip: {
      theme: "dark",
    },
  };
  document.getElementById("sales-profit").innerHTML = "";
  var chart = new ApexCharts(document.querySelector("#sales-profit"), options);
  chart.render();

  // =====================================
  // Sales Profit End
  // =====================================

  // =====================================
  // Return Investment chart
  // =====================================

  var returninvestment = {
    series: [
      {
        name: "",
        data: [128, 193, 150, 120, 174, 150],
      },
    ],

    chart: {
      toolbar: {
        show: false,
      },
      height: 230,
      type: "bar",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    colors: [
      "rgba(0,0,0,0.07)",
      "var(--bs-primary)",
      "rgba(0,0,0,0.07)",
      "rgba(0,0,0,0.07)",
      "rgba(0,0,0,0.07)",
      "rgba(0,0,0,0.07)",
    ],
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: "59%",
        distributed: true,
        endingShape: "rounded",
      },
    },

    dataLabels: {
      enabled: false,
    },
    legend: {
      show: false,
    },
    grid: {
      yaxis: {
        lines: {
          show: false,
        },
      },
      xaxis: {
        lines: {
          show: false,
        },
      },
    },
    xaxis: {
      categories: [["JAN"], ["FEB"], ["MAR"], ["APR"], ["MAY"], ["JUN"]],
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      labels: {
        show: false,
      },
    },
    tooltip: {
      theme: "dark",
    },
  };

  var chart = new ApexCharts(
    document.querySelector("#return-investment"),
    returninvestment
  );
  chart.render();

  // =====================================
  // total-followers chart
  // =====================================

  var totalfollowers = {
    series: [
      {
        name: "",
        data: [29, 52, 38, 47, 56],
      },
      {
        name: "",
        data: [71, 71, 71, 71, 71],
      },
    ],
    chart: {
      fontFamily: "inherit",
      type: "bar",
      height: 100,
      stacked: true,
      toolbar: {
        show: false,
      },
      sparkline: {
        enabled: true,
      },
    },
    grid: {
      show: false,
      borderColor: "rgba(0,0,0,0.1)",
      strokeDashArray: 1,
      xaxis: {
        lines: {
          show: false,
        },
      },
      yaxis: {
        lines: {
          show: true,
        },
      },
      padding: {
        top: 0,
        right: 0,
        bottom: 0,
        left: 0,
      },
    },
    colors: [
      "var(--bs-danger)",
      "var(--black-black-10, rgba(17, 28, 45, 0.10))",
    ],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "30%",
        borderRadius: [3],
        borderRadiusApplication: "end",
        borderRadiusWhenStacked: "all",
      },
    },
    dataLabels: {
      enabled: false,
    },
    xaxis: {
      labels: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
    },
    yaxis: {
      labels: {
        show: false,
      },
    },
    tooltip: {
      theme: "dark",
    },
    legend: {
      show: false,
    },
  };

  var chart_column_stacked = new ApexCharts(
    document.querySelector("#total-followers"),
    totalfollowers
  );
  chart_column_stacked.render();

  // =====================================
  // total-income
  // =====================================
  var options = {
    chart: {
      id: "total-income",
      type: "area",
      height: 70,
      sparkline: {
        enabled: true,
      },
      group: "sparklines",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        name: "monthly earnings",
        color: "var(--bs-secondary)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },

    markers: {
      size: 0,
    },
    tooltip: {
      theme: "dark",
      fixed: {
        enabled: true,
        position: "right",
      },
      x: {
        show: false,
      },
    },
  };
  new ApexCharts(document.querySelector("#total-income"), options).render();

  var chart3 = {
    color: "#adb5bd",
    series: [70, 49],
    labels: ["2022", "2021", "2020"],
    chart: {
      height: 150,
      type: "donut",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
      offsetY: -10,
    },
    plotOptions: {
      pie: {
        startAngle: 0,
        endAngle: 360,
        donut: {
          size: "85%",
        },
      },
    },
    stroke: {
      show: false,
      colors: "var(--bs-card-bg)",
      width: 3,
    },
    dataLabels: {
      enabled: false,
    },

    legend: {
      show: false,
    },
    colors: ["#b2efe8", "var(--bs-success)"],

    tooltip: {
      theme: "dark",
      fillSeriesColor: false,
    },
  };

  new ApexCharts(document.querySelector("#current-balance"), chart3).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-success)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart2",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart2",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-danger)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart2"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart3",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart3",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-success)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart3"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart4",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart4",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-warning)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart4"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart5",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart5",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-secondary)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart5"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart6",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart6",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-danger)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart6"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart7",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart7",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-primary)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart7"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart8",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart8",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-secondary)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart8"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart9",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart9",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-success)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart9"), options).render();

  // =====================================
  // table-chart
  // =====================================
  var options = {
    chart: {
      id: "table-chart10",
      type: "area",
      width: 143,
      height: 14,
      sparkline: {
        enabled: true,
      },
      group: "table-chart10",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        color: "var(--bs-secondary)",
        data: [25, 66, 20, 40, 12, 58, 20],
      },
    ],
    stroke: {
      curve: "smooth",
      width: 2,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0,
        opacityTo: 0,
        stops: [20, 180],
      },
    },
    markers: {
      size: 0,
    },
    tooltip: {
      enabled: false,
    },
  };
  new ApexCharts(document.querySelector("#table-chart10"), options).render();

  // Multiple Series Radar Chart -------> RADAR CHART
  var options_multiple = {
    series: [
      { name: "Sales", data: [32, 27, 27, 30, 25, 25] },
      { name: "Visits", data: [25, 35, 20, 20, 20, 20] },
    ],

    chart: {
      fontFamily: "inherit",
      type: "radar",
      height: 350,
      toolbar: { show: !1 },
      foreColor: "#adb0bb",
    },
    plotOptions: {
      radar: {
        polygons: {
          strokeColors: "var(--bs-border-color)",
          connectorColors: "var(--bs-border-color)",
        },
      },
    },
    colors: ["var(--bs-primary)", "var(--bs-danger-bg-subtle)"],
    legend: {
      show: false,
    },
    fill: {
      colors: ["var(--bs-primary)", "var(--bs-danger-bg-subtle)"],
      opacity: [1, 0.4],
    },
    markers: { size: 0 },
    grid: {
      show: false,
    },
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
      labels: {
        show: !0,
        style: {
          fontSize: "13px",
        },
      },
    },
    yaxis: { show: !1, min: 0, max: 40, tickAmount: 4 },
    responsive: [{ breakpoint: 769, options: { chart: { height: 400 } } }],
    tooltip: {
      theme: "dark",
    },
  };

  var chart_radar_multiple_series = new ApexCharts(
    document.querySelector("#chart-radar-multiple-series"),
    options_multiple
  );
  chart_radar_multiple_series.render();

  // =====================================
  // gauge-chart Start
  // =====================================

  var options = {
    series: [20, 20, 20, 20, 20],
    labels: ["245", "45", "14", "78", "95"],
    chart: {
      height: 200,
      fontFamily: "inherit",
      type: "donut",
    },
    plotOptions: {
      pie: {
        startAngle: -90,
        endAngle: 90,
        offsetY: 10,
        donut: {
          size: "85%",
        },
      },
    },
    grid: {
      padding: {
        bottom: -80,
      },
    },
    legend: {
      show: false,
    },
    dataLabels: {
      enabled: false,
      name: {
        show: false,
      },
    },
    stroke: {
      width: 2,
      colors: "var(--bs-card-bg)",
    },
    tooltip: {
      fillSeriesColor: false,
    },
    colors: [
      "var(--bs-danger)",
      "var(--bs-warning)",
      "var(--bs-warning-bg-subtle)",
      "var(--bs-success-bg-subtle)",
      "var(--bs-success)",
    ],
  };

  var chart = new ApexCharts(
    document.querySelector("#marketing-report"),
    options
  );
  chart.render();

  // -----------------------------------------------------------------------
  // Delivery Analytics
  // -----------------------------------------------------------------------

  var options_Sales_Overview = {
    series: [
      {
        name: "This year ",
        data: [9, 5, 3, 7, 5, 10, 3],
      },
      {
        name: "Last year ",
        data: [6, 3, 9, 5, 4, 6, 4],
      },
    ],
    chart: {
      fontFamily: "Inter,sans-serif",
      type: "bar",
      height: 330,
      offsetY: 10,
      toolbar: {
        show: false,
      },
    },
    grid: {
      show: true,
      strokeDashArray: 3,
      borderColor: "rgba(0,0,0,.1)",
    },
    colors: ["var(--bs-primary)", "var(--bs-secondary)"],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "30%",
        endingShape: "rounded",
        borderRadius: 6,
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      width: 5,
      colors: ["transparent"],
    },
    xaxis: {
      type: "category",
      categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
      labels: {
        style: {
          colors: "#a1aab2",
        },
      },
    },
    yaxis: {
      labels: {
        style: {
          colors: "#a1aab2",
        },
      },
    },
    fill: {
      opacity: 1,
      colors: ["var(--bs-primary)", "var(--bs-secondary)"],
    },
    tooltip: {
      theme: "dark",
    },
    legend: {
      show: false,
    },
    responsive: [
      {
        breakpoint: 767,
        options: {
          stroke: {
            show: false,
            width: 5,
            colors: ["transparent"],
          },
        },
      },
    ],
  };

  var chart_column_basic = new ApexCharts(
    document.querySelector("#delivery-analytics"),
    options_Sales_Overview
  );
  chart_column_basic.render();
});
