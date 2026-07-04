import { CURRENT_MONTH_RESERVATION_SALES_PER_DAY } from '../../../../config/constant';

export function SalesSupportChartData1() {
  return {
    height: 85,
    type: 'bar',
    options: {
      chart: {
        sparkline: {
          enabled: true
        },
        background: 'transparent'
      },
      colors: ['#7267EF'],
      plotOptions: {
        bar: {
          columnWidth: '70%'
        }
      },
      xaxis: {
        crosshairs: {
          width: 1
        }
      },
      tooltip: {
        fixed: {
          enabled: false
        },
        x: {
          show: false
        },
        y: {
          title: {
            formatter() {
              return '';
            }
          }
        },
        marker: {
          show: false
        }
      },
      theme: {
        mode: 'light'
      }
    },
    series: [
      {
        data: CURRENT_MONTH_RESERVATION_SALES_PER_DAY
      }
    ]
  };
}
