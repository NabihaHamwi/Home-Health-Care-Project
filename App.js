import './App.css';
import {RouterProvider, Route , createBrowserRouter,createRoutesFromElements } from 'react-router-dom';
import Layout from './Components/Layout/Layout.js';
import{Fragment} from 'react';
// import ReactDOM from 'react-dom';
import Home from './pages/Home.js';
import ShowServices from './Components/Show/show.js';


import PatientState from './Components/Patient/Pstate.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import Monitor from './Components/Monitor/Monitor.js';
import Nstate from './Components/Nurse/Nstate.js';
import Noptions from './Components/Options/Noptions.js';
// import Footer from "./Components/Footer/Footer.js";

// const patientData = {
//   // هنا يمكنك تعيين البيانات الافتراضية أو جلبها من الخادم
//   bloodPressure: '120/80',
//   bpTime: '08:00',
//   heartRate: '70',
//   hrTime: '08:00',
//   oxygenLevel: '98%',
//   oxygenTime: '08:00',
//   bloodSugar: '90 mg/dL',
//   sugarTime: '08:00',
//   // يمكنك إضافة المزيد من البيانات هنا
// };

const routes = createBrowserRouter(createRoutesFromElements(
  <Route path='/' element={<Layout/>}>
    <Route index path='/home' element={<Home/>}/>
    <Route index path='/ShowServices' element={<ShowServices/>}/>
  </Route>
))

function App() {
  return (
    <Fragment>
    <RouterProvider router={routes}/>
   


    <Noptions/>

    <Monitor/>

    < Nstate/>

<PatientState/>





    </Fragment>
   
  );
}

export default App;
