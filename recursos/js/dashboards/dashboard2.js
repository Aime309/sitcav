document.addEventListener("DOMContentLoaded", function () {
  // =====================================
  // Sales Profit Start
  // =====================================

  var options = {
    series: [
      {
        type: "area",
        name: "This Year",
        chart: {
          foreColor: "#111c2d99",
          fontSize: 12,
          fontWeight: 500,
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
            y: 25,
          },
          {
            x: "Sep",
            y: 25,
          },
          {
            x: "Oct",
            y: 10,
          },
          {
            x: "Nov",
            y: 10,
          },
          {
            x: "Dec",
            y: 45,
          },
          {
            x: "Jan",
            y: 45,
          },
          {
            x: "Feb",
            y: 75,
          },
          {
            x: "Mar",
            y: 70,
          },
          {
            x: "Apr",
            y: 35,
          },
        ],
      },
      {
        type: "line",
        name: "Last Year",
        chart: {
          foreColor: "#111c2d99",
        },
        data: [
          {
            x: "Aug",
            y: 50,
          },
          {
            x: "Sep",
            y: 50,
          },
          {
            x: "Oct",
            y: 25,
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
            y: 20,
          },
          {
            x: "Feb",
            y: 35,
          },
          {
            x: "Mar",
            y: 35,
          },
          {
            x: "Apr",
            y: 60,
          },
        ],
      },
    ],
    chart: {
      height: 300,
      fontFamily: "inherit",
      foreColor: "#adb0bb",
      fontSize: "12px",
      offsetX: -15,
      offsetY: 10,
      animations: {
        speed: 500,
      },
      toolbar: {
        show: false,
      },
    },
    colors: ["var(--bs-primary)", "var(--bs-secondary-color)"],
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
      show: true,
      strokeDashArray: 3,
      borderColor: "#90A4AE50",
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
    yaxis: {
      tickAmount: 3,
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
  // product-sales Start
  // =====================================

  var options = {
    series: [30, 10, 16, 16, 16, 10],
    labels: ["36%", "10%", "16%", "16%", "10%", "15%"],
    chart: {
      type: "donut",
      fontFamily: "inherit",
      foreColor: "#c6d1e9",
    },
    plotOptions: {
      pie: {
        startAngle: -90,
        endAngle: 90,
        offsetY: 10,
        donut: {
          size: "75%",
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: "15px",
              color: undefined,
              offsetY: 5,
              color: "var(--bs-dark)",
            },
            value: {
              show: false,
            },
            total: {
              show: true,
              color: "var(--bs-gray-400)",
              fontSize: "20px",
              fontWeight: "600",
              label: "8364",
            },
          },
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
      width: 0,
    },
    tooltip: {
      enabled: false,
      fillSeriesColor: false,
    },
    colors: [
      "var(--bs-primary)",
      "var(--bs-secondary)",
      "var(--bs-danger-bg-subtle)",
      "var(--bs-body-bg)",
      "var(--bs-gray-200)",
      "var(--bs-success)",
    ],
  };

  var chart = new ApexCharts(document.querySelector("#product-sales"), options);
  chart.render();

  // =====================================
  // product-sales End
  // =====================================


  // =====================================
  // marketing report chart
  // =====================================

  var marketingreport = {
    color: "#adb5bd",
    series: [70, 18, 12],
    labels: ["24.3K", "1.22", "+2.9k"],
    chart: {
      height: 180,
      type: "donut",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    stroke: {
      show: true,
      colors: "var(--bs-card-bg)",
      width: 3,
    },
    dataLabels: {
      enabled: false,
    },

    legend: {
      show: false,
    },
    colors: ["var(--bs-primary)", "var(--bs-success", "var(--bs-danger"],

    plotOptions: {
      pie: {
        startAngle: 0,
        endAngle: 360,
        donut: {
          size: "85%",
          background: "none",
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: "15px",
              color: undefined,
              offsetY: 5,
              color: "var(--bs-gray-400)",
            },
            value: {
              show: false,
            },
            total: {
              show: true,
              color: "var(--bs-gray-400)",
              fontSize: "20px",
              fontWeight: "600",
              label: "24.3k",
            },
          },
        },
      },
    },

    tooltip: {
      theme: "dark",
      fillSeriesColor: false,
    },
  };

  var chart = new ApexCharts(document.querySelector("#marketing-report"), marketingreport);
  chart.render();



  // =====================================
  // total-orders chart
  // =====================================

  var total_orders = {
    series: [
      {
        name: "Paypal",
        data: [29, 52, 38, 47, 56, 41, 46],
      },
      {
        name: "Credit Card",
        data: [71, 71, 71, 71, 71, 71, 71],
      },
    ],
    chart: {
      fontFamily: "inherit",
      foreColor: "#adb0bb",
      type: "bar",
      height: 150,
      stacked: true,
      offsetX: -20,
      toolbar: {
        show: false,
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
    colors: ["var(--bs-primary)", "#D9D9D955"],
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "26%",
        borderRadius: [3],
        borderRadiusApplication: "end",
        borderRadiusWhenStacked: "all",
      },
    },
    dataLabels: {
      enabled: false,
    },
    xaxis: {
      categories: [["M"], ["T"], ["W"], ["T"], ["F"], ["S"], ["S"]],
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
    document.querySelector("#total-orders"),
    total_orders
  );
  chart_column_stacked.render();


  // -----------------------------------------------------------------------
  // Annual Profit
  // -----------------------------------------------------------------------
  var options = {
    chart: {
      id: "annual-profit",
      type: "area",
      height: 80,
      sparkline: {
        enabled: true,
      },
      group: "sparklines",
      fontFamily: "inherit",
      foreColor: "#adb0bb",
    },
    series: [
      {
        name: "Users",
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
      color: "var(--bs-primary)",

      gradient: {
        shadeIntensity: 0,
        inverseColors: false,
        opacityFrom: 0.2,
        opacityTo: 0.1,
        stops: [100],
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
  new ApexCharts(document.querySelector("#annual-profit"), options).render();

  // -----------------------------------------------------------------------
  // Sales overview
  // -----------------------------------------------------------------------

  var options_Sales_Overview = {
    series: [
      {
        name: "This year",
        data: [9, 5, 3, 7, 5, 10, 3],
      },
      {
        name: "Last year ",
        data: [6, 3, 9, 5, 4, 6, 4],
      },
    ],
    chart: {
      fontFamily: "inherit",
      type: "bar",
      height: 300,
      offsetY: 10,
      offsetX: -18,
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
        borderRadius: 5,
        horizontal: false,
        columnWidth: "30%",
        endingShape: "rounded",
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
    document.querySelector("#sales-profit2"),
    options_Sales_Overview
  );
  chart_column_basic.render();
});
