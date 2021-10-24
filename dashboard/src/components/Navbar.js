import React from "react";
import { Navbar, Nav, Container } from "react-bootstrap";
import { LinkContainer } from "react-router-bootstrap";

const header = () => {
  return (
    <Navbar bg="dark" variant="dark" className="nav">
      <Container>
        <LinkContainer to="/">
          <Navbar.Brand>Solferino Academy Dashboard</Navbar.Brand>
        </LinkContainer>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mr-auto">
            <LinkContainer to="/">
              <Nav.Link>Home</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/features">
              <Nav.Link>Features</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/perfomancetesting">
              <Nav.Link>Performance Testing</Nav.Link>
            </LinkContainer>
            <LinkContainer to="/accessibilitytesting">
              <Nav.Link>Accessibility Testing</Nav.Link>
            </LinkContainer>
            <Nav.Link href="https://staging-sa.actionit.dev/" target="_blank">
              Staging Site
            </Nav.Link>
            <Nav.Link
              href="https://production-sa.actionit.dev/"
              target="_blank"
            >
              Production Site
            </Nav.Link>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
};

export default header;
