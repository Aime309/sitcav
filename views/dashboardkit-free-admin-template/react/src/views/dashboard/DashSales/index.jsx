// react-bootstrap
import { Row, Col, Card } from 'react-bootstrap';

// third party
import Chart from 'react-apexcharts';

// project imports
import FlatCard from '../../../components/Widgets/Statistic/FlatCard';
import ProductCard from '../../../components/Widgets/Statistic/ProductCard';
import FeedTable from '../../../components/Widgets/FeedTable';
import ProductTable from '../../../components/Widgets/ProductTable';
import { SalesCustomerSatisfactionChartData } from './chart/sales-customer-satisfication-chart';
import { SalesAccountChartData } from './chart/sales-account-chart';
import { SalesSupportChartData } from './chart/sales-support-chart';
import { SalesSupportChartData1 } from './chart/sales-support-chart1';
import feedData from '../../../data/feedData';
import productData from '../../../data/productTableData';
import { AVERAGE_PRICE, CLIENTS, CONVERSION_RATE_PERCENTAGE, CURRENT_YEAR_SALES, CURRENT_YEAR_SALES_AVERAGE, PRODUCT_SOLD, RESERVATION_SALES, RESERVATION_SALES_PER_MONTH, RESERVATIONS_PER_YEAR, RETURNS, REVENUE, TOTAL_PROFIT, TOTAL_RESERVATIONS } from '../../../config/constant';

const MONTH_NAMES = {
  1: 'Enero',
  2: 'Febrero',
  3: 'Marzo',
  4: 'Abril',
  5: 'Mayo',
  6: 'Junio',
  7: 'Julio',
  8: 'Agosto',
  9: 'Septiembre',
  10: 'Octubre',
  11: 'Noviembre',
  12: 'Diciembre',
}

// -----------------------|| DASHBOARD SALES ||-----------------------//
export default function DashSales() {
  return (
    <Row>
      <Col md={12} xl={6}>
        <Card className="flat-card">
          <div className="row-table">
            <Card.Body className="col-sm-6 br p-3">
              <FlatCard params={{ title: 'Clientes', iconClass: 'text-primary mb-1', icon: 'group', value: CLIENTS }} />
            </Card.Body>
            <Card.Body className="col-sm-6 d-none d-md-table-cell d-lg-table-cell d-xl-table-cell card-body br">
              <FlatCard params={{ title: 'Ingresos', iconClass: 'text-primary mb-1', icon: 'language', value: REVENUE }} />
            </Card.Body>
            <Card.Body className="col-sm-6 br">
              <FlatCard
                params={{
                  title: 'Devoluciones',
                  iconClass: 'text-primary mb-1',
                  icon: 'swap_horizontal_circle',
                  value: RETURNS
                }}
              />
            </Card.Body>
          </div>
          {/*<div className="row-table">
            <Card.Body className="col-sm-6 card-bod">
              <FlatCard params={{ title: 'Crecimiento', iconClass: 'text-primary mb-1', icon: 'unarchive', value: '600' }} />
            </Card.Body>
            <Card.Body className="col-sm-6 d-none d-md-table-cell d-lg-table-cell d-xl-table-cell card-body br">
              <FlatCard params={{ title: 'Descargas', iconClass: 'text-primary mb-1', icon: 'cloud_download', value: '3550' }} />
            </Card.Body>
            <Card.Body className="col-sm-6 card-bod">
              <FlatCard params={{ title: 'Orden', iconClass: 'text-primary mb-1', icon: 'shopping_cart', value: '100%' }} />
            </Card.Body>
          </div>*/}
        </Card>
        <Row>
          <Col md={6}>
            <Card className="support-bar overflow-hidden">
              <Card.Body className="pb-0">
                <h2 className="m-0">{CONVERSION_RATE_PERCENTAGE}%</h2>
                <span className="text-primary">Tasa de Conversión</span>
                <p className="mb-3 mt-3">Número de visitantes que reservaron.</p>
              </Card.Body>
              <Chart {...SalesSupportChartData()} />
              <Card.Footer className="border-0 bg-primary text-white background-pattern-white">
                <Row className="text-center">
                  {Object.entries(RESERVATIONS_PER_YEAR).map(([year, reservations]) => (
                    <Col>
                      <h4 className="m-0 text-white">{reservations}</h4>
                      <span>{year}</span>
                    </Col>
                  ))}
                </Row>
              </Card.Footer>
            </Card>
          </Col>
          <Col md={6}>
            <Card className="support-bar overflow-hidden">
              <Card.Body className="pb-0">
                <h2 className="m-0">{RESERVATION_SALES}</h2>
                <span className="text-primary">Reservas Vendidas</span>
                <p className="mb-3 mt-3">Número de reservas que resultaron en ventas.</p>
              </Card.Body>
              <Card.Footer className="border-0">
                <Row className="text-center">
                  {Object.entries(RESERVATION_SALES_PER_MONTH).map(([monthNumber, reservationSales]) => (
                    <Col>
                      <h4 className="m-0">{reservationSales}</h4>
                      <span>{MONTH_NAMES[monthNumber]}</span>
                    </Col>
                  ))}
                </Row>
              </Card.Footer>
              <Chart type="bar" {...SalesSupportChartData1()} />
            </Card>
          </Col>
        </Row>
      </Col>
      <Col md={12} xl={6}>
        <Card>
          <Card.Header>
            <h5>Informe de ventas mensual</h5>
          </Card.Header>
          <Card.Body>
            <Row className="pb-2">
              <div className="col-auto m-b-10">
                <h3 className="mb-1">${CURRENT_YEAR_SALES}</h3>
                <span>Ventas Totales</span>
              </div>
              <div className="col-auto m-b-10">
                <h3 className="mb-1">${CURRENT_YEAR_SALES_AVERAGE}</h3>
                <span>Promedio</span>
              </div>
            </Row>
            <Chart {...SalesAccountChartData()} />
          </Card.Body>
        </Card>
      </Col>
      <Col md={12} xl={6}>
        <Card>
          <Card.Body>
            <h6>Satisfacción del Cliente</h6>
            <span>Se necesita un esfuerzo continuo para mantener altos niveles de satisfacción del cliente interno y externo.</span>
            <Row className="d-flex justify-content-center align-items-center">
              <Col>
                <Chart type="pie" {...SalesCustomerSatisfactionChartData()} />
              </Col>
            </Row>
          </Card.Body>
        </Card>
        {/* Product Table */}
        <ProductTable {...productData} />
      </Col>
      <Col md={12} xl={6}>
        <Row>
          <Col sm={6}>
            <ProductCard params={{ title: 'Ganancia Total', primaryText: `$${TOTAL_PROFIT}`, icon: 'card_giftcard' }} />
          </Col>
          <Col sm={6}>
            <ProductCard params={{ variant: 'primary', title: 'Reservas Totales', primaryText: TOTAL_RESERVATIONS, icon: 'local_mall' }} />
          </Col>
          <Col sm={6}>
            <ProductCard params={{ variant: 'primary', title: 'Precio Promedio', primaryText: `$${AVERAGE_PRICE}`, icon: 'monetization_on' }} />
          </Col>
          <Col sm={6}>
            <ProductCard params={{ title: 'Productos Vendidos', primaryText: PRODUCT_SOLD, icon: 'local_offer' }} />
          </Col>
        </Row>
        {/* Feed Table */}
        <FeedTable {...feedData} />
      </Col>
    </Row>
  );
}
