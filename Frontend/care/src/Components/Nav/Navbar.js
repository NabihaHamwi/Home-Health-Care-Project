import React from "react";
import './Nav.css';
import log from '../../Assets/log.jpg';
import {Navbar , Container , NavDropdown, Collapse,Nav,Img } from 'react-bootstrap';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faSearch} from '@fortawesome/free-solid-svg-icons'
import {faPhone} from '@fortawesome/free-solid-svg-icons'
import { Link } from "react-router-dom";

const Navbars = ()=>{
    return(
        <Navbar expand="lg" className="navs">
        <Container>
          <Navbar.Brand>
          <img src={log} title="logo" className="logo"/>
          </Navbar.Brand>
         
          <Navbar.Toggle aria-controls="basic-navbar-nav" />
          <Navbar.Collapse id="basic-navbar-nav">
            <Nav className="me-auto">
              <Link to="/" className="active">Home</Link>
              <NavDropdown title="pages" id="basic-nav-dropdown">
                <Link to="/about">About us</Link>
                <Link to="/team">Our Team</Link>                            
              </NavDropdown>
              <NavDropdown title="Services" id="basic-nav-dropdown">
                <Link to="/Show-Services">Services</Link>
                <Link to="/sdetail">Services Details</Link>
              </NavDropdown>
              <NavDropdown title="Blog" id="basic-nav-dropdown">
                <Link to="/blog">Blog</Link>
                <Link to="/detail">Blog Details</Link>
              </NavDropdown>
              {/* <Link to="/contact" >Contact us</Link> */}
              <Nav.Link ><FontAwesomeIcon icon={faSearch}/></Nav.Link>
              <Nav.Link >
              <FontAwesomeIcon icon={faPhone}/>
                 + 787878</Nav.Link>
              <Nav.Link >
                <button>Contact us <span> &gt; </span></button>
              </Nav.Link>

            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>
    )
}
export default Navbars;