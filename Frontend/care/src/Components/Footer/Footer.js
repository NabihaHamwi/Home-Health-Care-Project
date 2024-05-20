import React from "react";
import footerlogo from '../../Assets/log.jpg';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faPhone} from '@fortawesome/free-solid-svg-icons'
import facebook from "../../Assets/face.png"
import twitter from "../../Assets/twit.png"
import instagram from "../../Assets/insta.png"
import linkedin from "../../Assets/linked.png"
const Footer =()=>{
 return(
    <footer>
        <div className="container">
          <div className="row">

        <div className="col-md-3 col-sm-6">
          <img src={footerlogo} className="footerlogo"/>
           <p>Lorem ipsum is dolor sit amet, csectetur adipiscing elit, dolore smod tempor incididunt ut labore et.  </p>
            <div className="footer-contact">
                <div className="footer-icon">
                 <FontAwesomeIcon icon={faPhone} />
                      
                </div>
                <div className="footer-text">
                    <h6>Contact us</h6>
                    <h4>+8 44494 84950</h4>
                    
                </div>
            </div>
      
        </div>

        <div className="col-md-3 col-sm-6">
         <h2>Quick linkes</h2>
         <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Booking</a></li>
            <li><a href="#">Faqs</a></li>
            <li><a href="#">Our Services</a></li>
            <li><a href="#">Our Team</a></li>
         </ul>
        </div>

        <div className="col-md-3 col-sm-6">
        <h2>Our Services</h2>
         <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Booking</a></li>
            <li><a href="#">Faqs</a></li>
            <li><a href="#">Our Services</a></li>
            <li><a href="#">Our Team</a></li>
         </ul>
        </div>
        <div className="col-md-3 col-sm-6">
        <h2>subscribe</h2>
        <form> 
            <input type="email" placeholder="Enter Your Email"/>
            <button type="submit">subscribe Now</button>
        </form>
        <ul className="social">
            <li><a href="#"><img src={facebook}/></a></li>
            <li><a href="#"><img src={twitter}/></a></li>
            <li><a href="#"><img src={instagram}/></a></li>
            <li><a href="#"><img src={linkedin}/></a></li>
        </ul>
        </div>
          </div>  
    
        </div>
        <div className="footer-bootom">
            <div className="container">
                <div className="row">
                    <div className="col-lg-12 col-md-12">
                        <span>Copyright &copy; 2024 Design & Developed by SLN Team </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
 )
}
export default Footer;