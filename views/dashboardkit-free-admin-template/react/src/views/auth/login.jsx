import { NavLink } from 'react-router-dom';

// react-bootstrap
import { Card, Row, Col, Button, Form, InputGroup } from 'react-bootstrap';

// third party
import FeatherIcon from 'feather-icons-react';

// assets
import logoDark from '../../assets/images/logo-dark.png';

// -----------------------|| SIGNIN 1 ||-----------------------//

export default function SignIn1() {
  return (
    <div className="auth-wrapper">
      <div className="auth-content text-center">
        <Card className="borderless">
          <Row className="align-items-center text-center">
            <Col>
              <Card.Body className="card-body">
                <img src={logoDark} className="img-fluid mb-4" />
                <h4 className="mb-3 f-w-400">Iniciar Sesión</h4>
                <Form method="post">
                  <InputGroup className="mb-3">
                    <InputGroup.Text>
                      <FeatherIcon icon="mail" />
                    </InputGroup.Text>
                    <Form.Control type="email" placeholder="Dirección de correo electrónico" name="email" />
                  </InputGroup>
                  <InputGroup className="mb-3">
                    <InputGroup.Text>
                      <FeatherIcon icon="lock" />
                    </InputGroup.Text>
                    <Form.Control type="password" placeholder="Contraseña" name="password" />
                  </InputGroup>
                  <Form.Group>
                    <Form.Check
                      type="checkbox"
                      className="text-left mb-4 mt-2"
                      label="Guardar Credenciales."
                      defaultChecked
                      name="remember"
                    />
                  </Form.Group>
                  <Button className="btn btn-block btn-primary mb-4" type="submit">
                    Iniciar Sesión
                  </Button>
                </Form>
                <p className="mb-2 text-muted">
                  ¿Olvidó su contraseña?&nbsp;
                  <NavLink to="#" className="f-w-400">
                    Reasignar
                  </NavLink>
                </p>
                <p className="mb-0 text-muted">
                  ¿No tienes una cuenta?&nbsp;
                  <NavLink to="/register" className="f-w-400">
                    Registrarse
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
