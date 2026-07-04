import { CURRENT_YEAR_RESERVATIONS_PER_MONTH } from '../../../../config/constant';

export function SalesSupportChartData() {
  return {
    type: 'area',
    height: 85,
    options: {
      chart: {
        width: '100%',
        sparkline: {
          enabled: true
        },
        background: 'transparent'
      },
      colors: ['#7267EF'],
      stroke: {
        curve: 'smooth',
        width: 2
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
              return 'Reservas ';
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
        data: CURRENT_YEAR_RESERVATIONS_PER_MONTH
      }
    ]
  };
}
