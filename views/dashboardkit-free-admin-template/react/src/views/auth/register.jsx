import { NavLink } from 'react-router-dom';

// react-bootstrap
import { Card, Row, Col, Button, InputGroup, Form } from 'react-bootstrap';

// third party
import FeatherIcon from 'feather-icons-react';

// assets
import logoDark from '../../assets/images/logo-dark.png';

// -----------------------|| SignUp 1 ||-----------------------//

export default function SignUp1() {
  return (
    <div className="auth-wrapper">
      <div className="auth-content text-center">
        <Card className="borderless">
          <Row className="align-items-center text-center">
            <Col>
              <Card.Body className="card-body">
                <img src={logoDark} className="img-fluid mb-4" />
                <h4 className="mb-3 f-w-400">Registrarse</h4>
                <Form method="post">
                  <InputGroup className="mb-3">
                    <InputGroup.Text>
                      <FeatherIcon icon="user" />
                    </InputGroup.Text>
                    <Form.Control type="text" placeholder="Nombre" name="name" />
                  </InputGroup>
                  <InputGroup className="mb-3">
                    <InputGroup.Text>
                      <FeatherIcon icon="mail" />
                    </InputGroup.Text>
                    <Form.Control type="email" placeholder="Dirección de correo electrónico" name="email" />
                  </InputGroup>
                  <InputGroup className="mb-4">
                    <InputGroup.Text>
                      <FeatherIcon icon="lock" />
                    </InputGroup.Text>
                    <Form.Control type="password" placeholder="Contraseña" name="password" />
                  </InputGroup>
                  <Button className="btn-block mb-4" type="submit">
                    Registrarse
                  </Button>
                </Form>
                <p className="mb-2">
                  ¿Ya tienes una cuenta?&nbsp;
                  <NavLink to="/login" className="f-w-400">
                    Iniciar Sesión
                  </NavLink>
                </p>
              </Card.Body>
            </Col>
          </Row>
        </Card>
      </div>
    </div>
  );
}
