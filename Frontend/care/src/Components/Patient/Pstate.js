
// import React, { useState } from 'react';
// import { Container, Form, Table, Button } from 'react-bootstrap';
// import 'bootstrap/dist/css/bootstrap.min.css';
// import './Pstate.css';

// const PatientState = () => {
//   const [patientData, setPatientData] = useState({
//     currentDate: '',
//     bloodPressure: '',
//     bpTime: '',
//     heartRate: '',
//     hrTime: '',
//     oxygenLevel: '',
//     oxygenTime: '',
//     bloodSugar: '',
//     sugarTime: '',
//     consciousnessLevel: '',
//     consciousnessTime: '',
//     conditionChanges: '',
//     conditionTime: '',
//     medicalPrescription: '',
//     activity: '',
//     activityValue: '',
//     activityTime: ''
//   });

//   const [activities, setActivities] = useState([]);

  // const handleChange = (e) => {
  //   setPatientData({ ...patientData, [e.target.name]: e.target.value });
  // };

  // const addActivity = () => {
  //   // إضافة النشاط الجديد إلى قائمة الأنشطة
  //   const newActivity = {
  //     activity: patientData.activity,
  //     activityValue: patientData.activityValue,
  //     activityTime: patientData.activityTime
  //   };
  //   setActivities(prevActivities => [...prevActivities, newActivity]);
  
  //   // إعادة تعيين قيم النشاط في patientData
  //   setPatientData(prevPatientData => ({
  //     ...prevPatientData,
  //     activity: '',
  //     activityValue: '',
  //     activityTime: ''
  //   }));
  // };

//   const handleSubmit = (e) => {
//     e.preventDefault();
//     // تعيين الـ URL للخادم الخاص بك
//     const serverURL = 'عنوان الخادم الخاص بك';

//     fetch(serverURL, {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//       },
//       body: JSON.stringify({ ...patientData, activities }),
//     })
//     .then(response => response.json())
//     .then(data => {
//       console.log('Success:', data);
//       // يمكنك هنا التعامل مع البيانات المستلمة من الخادم
//     })
//     .catch((error) => {
//       console.error('Error:', error);
//     });
//   };

//   return (
//     <Container className="dashboard">
//       <h2 className="text-center mb-4">لوحة متابعة المريض</h2>
      // <Form onSubmit={handleSubmit} className="patient-form">
      //   <Form.Group className="mb-3">
      //     <Form.Label>التاريخ:</Form.Label>
      //     <Form.Control
      //       type="date"
      //       name="currentDate"
      //       value={patientData.currentDate}
      //       onChange={handleChange}
      //     />
      //   </Form.Group>
//         <Table responsive="md" striped bordered hover className="text-center patient-table">
//           <thead>
//             <tr>
//               <th>العلامة الحيوية</th>
//               <th>القيمة</th>
//               <th>الوقت</th>
//             </tr>
//           </thead>
//           <tbody>
//             <tr>
//               <td>ضغط الدم</td>
//               <td>
//                 <Form.Control
//                   type="text"
//                   name="bloodPressure"
//                   value={patientData.bloodPressure}
//                   onChange={handleChange}
//                 />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="bpTime"
//                   value={patientData.bpTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             <tr>
//               <td>معدل نبضات القلب</td>
//               <td>
//                 <Form.Control
//                   type="text"
//                   name="heartRate"
//                   value={patientData.heartRate}
//                   onChange={handleChange}
//                 />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="hrTime"
//                   value={patientData.hrTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             <tr>
//               <td>مستوى الأكسجين</td>
//               <td>
//                 <Form.Control
//                   type="text"
//                   name="oxygenLevel"
//                   value={patientData.oxygenLevel}
//                   onChange={handleChange}


// />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="oxygenTime"
//                   value={patientData.oxygenTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             <tr>
//               <td>مستوى السكر في الدم</td>
//               <td>
//                 <Form.Control
//                   type="text"
//                   name="bloodSugar"
//                   value={patientData.bloodSugar}
//                   onChange={handleChange}
//                 />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="sugarTime"
//                   value={patientData.sugarTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             <tr>
//               <td>درجة الوعي</td>
//               <td>
//                 <Form.Control
//                   type="number"
//                   max="16"
//                   name="consciousnessLevel"
//                   value={patientData.consciousnessLevel}
//                   onChange={handleChange}
//                 />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="consciousnessTime"
//                   value={patientData.consciousnessTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             <tr>
//               <td>التغيرات في الحالة</td>
//               <td>
//                 <Form.Control
//                   type="text"
//                   name="conditionChanges"
//                   value={patientData.conditionChanges}
//                   onChange={handleChange}
//                 />
//               </td>
//               <td>
//                 <Form.Control
//                   type="time"
//                   name="conditionTime"
//                   value={patientData.conditionTime}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr>
//             {/* <tr>
//               <td>الوصفة الطبية</td>
//               <td colSpan="2">
//                 <Form.Control
//                   as="textarea"
//                   name="medicalPrescription"
//                   value={patientData.medicalPrescription}
//                   onChange={handleChange}
//                 />
//               </td>
//             </tr> */}
//             {/* إضافة الأنشطة */}
//             {activities.map((activity, index) => (
//               <tr key={index} >
//                 <td>
//                   <Form.Control
//                     as="select"
//                     name="activity"
//                     value={activity.activity}
//                     onChange={handleChange}
//                     className='center'
//                   >
//                     <option value="" disabled>اختر نشاط...</option>
//                     <option value="measurement">قياس</option>
//                     <option value="medication">تناول دواء</option>
//                     <option value="physicalTherapy">العلاج الطبيعي</option>
//                     <option value="consultation">استشارة</option>
                 
//                   </Form.Control>
//                 </td>
//                 <td>
//                   <Form.Control
//                     type="text"
//                     name="activityValue"
//                     value={activity.activityValue}
//                     onChange={handleChange}
//                   />
//                 </td>
//                 <td>
//                   <Form.Control
//                     type="time"
//                     name="activityTime"
//                     value={activity.activityTime}
//                     onChange={handleChange}
//                   />
//                 </td>
//               </tr>
//             ))}
//           </tbody>
//         </Table>
        // <div className="text-left bttn">
        //   <Button  onClick={addActivity} className="mt-3 add">
        //     إضافة نشاط
        //   </Button>
          
//         </div>
//          <div className="text-right btttn">
//           <Button  type="submit" className="mt-3 add">
//             تحديث البيانات
//           </Button>
//         </div>
//       </Form>
//     </Container>
//   );
// };

// export default PatientState;

import React, { useState, useEffect } from 'react';
import { Container, Form, Table, Button, Row, Col } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Pstate.css';

const PatientState = ({ appointment }) => {
  const [patientData, setPatientData] = useState({
    currentDate: '',
    startTime: '',
    vitalSigns: {}
  });

  useEffect(() => {
    const serverURL = 'http://127.0.0.1:8000/api/sessions/create/1';
    const fetchData = async () => {
      try {
        const response = await fetch(serverURL);
        const data = await response.json();
        if (data.status === 'success') {
          setPatientData({
            currentDate: data.data.appointment_date,
            startTime: '',
            vitalSigns: data.data.activities_name.reduce((acc, sign) => {
              acc[sign] = { value: '', time: '' };
              return acc;
            }, {})
          });
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchData();
  }, [appointment]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    const [key, subkey] = name.split('.');
    if (subkey) {
      setPatientData(prevPatientData => ({
        ...prevPatientData,
        vitalSigns: {
          ...prevPatientData.vitalSigns,
          [key]: { ...prevPatientData.vitalSigns[key], [subkey]: value }
        }
      }));
    } else {
      setPatientData(prevPatientData => ({
        ...prevPatientData,
        [name]: value
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const serverURL = 'http://127.0.0.1:8000/api/sessions';
    try {
      const response = await fetch(serverURL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          start_time: patientData.startTime,
          activities: Object.entries(patientData.vitalSigns).map(([sign, { value, time }]) => ({
            id: sign,
            value,
            time
          }))
        })
      });
      const data = await response.json();
      if (data.status === 'success') {
        console.log('Session stored successfully:', data);
      } else {
        console.error('Failed to store session:', data.message);
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleNewSession = () => {
    setPatientData(prevPatientData => ({
      ...prevPatientData,
      vitalSigns: Object.keys(prevPatientData.vitalSigns).reduce((acc, sign) => {
        acc[sign] = { value: '', time: '' };
        return acc;
      }, {})
    }));
  };

  return (
    <Container className="dashboard">
      <h2 className="text-center mb-4 title">جلسة متابعة المريض</h2>
      <Form onSubmit={handleSubmit} className="patient-form">
        <Row>
          <Col>
            <Form.Group className="mb-3">
              <Form.Label>التاريخ</Form.Label>
              <Form.Control
                type="date"
                name="currentDate"
                value={patientData.currentDate}
                onChange={handleChange}
                disabled
              />
            </Form.Group>
          </Col>
          <Col>
            <Form.Group className="mb-3">
              <Form.Label>وقت البدء</Form.Label>
              <Form.Control
                type="time"
                name="startTime"
                value={patientData.startTime}
                onChange={handleChange}
              />
            </Form.Group>
          </Col>
        </Row>
        <Table responsive="md" striped bordered hover className="text-center patient-table">
          <thead>
            <tr>
              <th>العلامة الحيوية</th>
              <th>القيمة</th>
              <th>الوقت</th>
            </tr>
          </thead>
          <tbody>
            {Object.entries(patientData.vitalSigns).map(([sign, { value, time }], index) => (
              <tr key={index}>
                <td>{sign}</td>
                <td>
                  <Form.Control
                    type="text"
                    name={`${sign}.value`}
                    value={value}
                    onChange={handleChange}
                  />
                </td>
                <td>
                  <Form.Control
                    type="time"
                    name={`${sign}.time`}
                    value={time}
                    onChange={handleChange}
                  />
                </td>
              </tr>
            ))}
          </tbody>
        </Table>
        <Button type="submit" className="mt-3 btnn">
          تحديث البيانات
        </Button>
        <Button onClick={handleNewSession} className="mt-3 ml-2 btnn">
           جلسة جديدة
        </Button>
      </Form>
    </Container>
  );
};

export default PatientState;
