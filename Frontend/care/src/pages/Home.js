import React from "react";
import headerimg from '../Assets/16.png'
import './Home.css';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome'
import {faSquare} from '@fortawesome/free-solid-svg-icons'
import background from  '../Assets/22.jpg';
// import Navbars from '../Nav/Navbar.js';



const Home=()=>{
    return(
        <header>
            {/* < Navbars/> */}
     <div className="container pos" >
        <div className="row">
            <div className="col-md-6 col-lg-6 ">
                <h5 >We provide all Health care solution</h5>
                 <h2>Protect your health and take care to of your health</h2>
                 <button><a href="#">Read More</a></button>
                 <span>+</span>
            </div>
             <div className="col-lg-6 col-md-6">
                 <div className="header-box">
                 <img src={headerimg}/>
               
                 <FontAwesomeIcon icon={faSquare} />
                 </div>
             </div>
        </div>

     </div>






        </header>
    )
}
export default Home;