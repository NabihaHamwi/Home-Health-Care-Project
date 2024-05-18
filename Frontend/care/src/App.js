import './App.css';
import { Route, Routes, BrowserRouter } from 'react-router-dom';
// import ReactDOM from 'react-dom';
import Home from './pages/Home.js';
import ShowServices from './Components/Show/show.js';
import PatientInfoForm from './Components/Info/info.js';
import Navbars from './Components/Nav/Navbar.js';
import PatientState from './Components/Patient/Pstate.js';
import 'bootstrap/dist/css/bootstrap.min.css';
import Monitor from './Components/Monitor/Monitor.js';
import Nstate from './Components/Nurse/Nstate.js';
import Noptions from './Components/Options/Noptions.js';
// import  WorkingTime from './Components/Wtime/wtime.js';
import Schedule from './Components/Appoint/appointment.js';
// import Evaluation from './Components/Evalution/evalution.js';
import AcceptanceInterface from './Components/Accept/accept.js'
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

// const routes = createBrowserRouter(createRoutesFromElements(
//   <Route path='/' element={<Layout/>}>
//     <Route index path='/home' element={<Home/>}/>
//     <Route index path='/ShowServices' element={<ShowServices/>}/>
//   </Route>
// ))
const providerId= [
  { id: 1},
  { id: 2 },
  // يمكنك إضافة المزيد هنا
];
const requests = [
  { id: 1, patientName: 'مريض 1', date: '2024-05-07', time: '08:00' },
  { id: 2, patientName: 'مريض 2', date: '2024-05-08', time: '09:00' },
  // يمكنك إضافة المزيد هنا
];
function App() {
  
  return (
    <BrowserRouter>
   
    <Navbars/>
    {/* <RouterProvider router={routes}/> */}
   <Routes>
    <Route path="/" element={<Home/>}/>
    {/* <Route path="/pages" exact element={<pages/>}/> */}
    {/* <Route path="/team" exact element={<Our Team/>}/> */}
    <Route  path="/nstate" element={<Nstate/>}/>
    <Route index path='/Show-Services' element={<ShowServices/>}/>
    <Route index path='/noptions' element={<Noptions/>}/>
    {/* <Route index path='/booking' element={<BookingCalendar  providerId={providerId}/>}/> */}
    
   </Routes>
   {/* <PatientInfoForm/>  */}
   {/* <PatientState/> */}

   {/* <Monitor/> */}

   {/* <PatientInfoForm/>  */}
   {/* <AcceptanceInterface requests={requests} /> */}

   {/* <Evaluation/> */}
    {/* <Noptions/> */}
   
   {/* <ShowServices/> */}
  
   {/* <Nstate/> */}

    {/* <Noptions/> */}

     {/* <Monitor/>
  

<PatientState/>



<PatientInfoForm/> 

// */}

{/* <Booking  providerId={providerId}/> */}
<Schedule providerId="123" />

{/* < WorkingTime/> */}

    </BrowserRouter>
  );
}

export default App;