
import React from 'react'
import {Navbar, Nav} from 'react-bootstrap'
import {LinkContainer} from 'react-router-bootstrap'

const header = () => {
    return (
      <Navbar bg="dark" variant="dark" className="nav">
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
      
    </Nav>
  </Navbar.Collapse>
</Navbar>
    )
}

export default header