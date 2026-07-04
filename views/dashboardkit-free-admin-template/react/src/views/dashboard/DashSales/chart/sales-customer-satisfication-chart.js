import { CUSTOMER_SATISFACTION } from "../../../../config/constant";

export function SalesCustomerSatisfactionChartData() {
  return {
    height: 260,
    options: {
      chart: {
        background: 'transparent'
      },
      labels: ['Extremadamente Satisfecho', 'Satisfecho', 'Insatisfecho', 'Muy Insatisfecho'],
      legend: {
        show: true,
        offsetY: 50
      },
      dataLabels: {
        enabled: true,
        dropShadow: {
          enabled: false
        }
      },
      theme: {
        mode: 'light',
        monochrome: {
          enabled: true,
          color: '#7267EF'
        }
      },
      responsive: [
        {
          breakpoint: 768,
          options: {
            chart: {
              height: 320
            },
            legend: {
              position: 'bottom',
              offsetY: 0
            }
          }
        }
      ]
    },
    series: Object.values(CUSTOMER_SATISFACTION)
  };
}
