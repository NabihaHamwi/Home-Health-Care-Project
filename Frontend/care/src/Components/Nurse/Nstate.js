// // Nstate.js
// import React, { useState, useEffect } from 'react';
// import { Form, Button, Card } from 'react-bootstrap';
// import './Nstate.css';
// import { Link } from 'react-router-dom';

// const Nstate = () => {
//   const [searchParams, setSearchParams] = useState({
//     service: '',
//     gender: '',
//     age: '',
//     physicalStrength: '',
//     experience: '',
//     skill: ''
//   });

//   const [skills, setSkills] = useState([]);
//   const [services, setServices] = useState([]);

//   useEffect(() => {
//     fetch('http://127.0.0.1:8000/api/search')
//       .then(response => {
//         if (!response.ok) {
//           throw new Error('Network response was not ok');
//         }
//         return response.json();
//       })
//       .then(data => {
//         setSkills(data.skills.original.data);
//         setServices(data.services.original.data);
//       })
//       .catch(error => {
//         console.error('Error:', error);
//       });
//   }, []);

//   const handleInputChange = (e) => {
//     const { name, value } = e.target;
//     setSearchParams(prevParams => ({
//       ...prevParams,
//       [name]: value
//     }));
//   };

//   const handleSubmit = (e) => {
//     e.preventDefault();
//     fetch('http://127.0.0.1:8000/api/search/result', {
//       method: 'GET',
//       headers: {
//         'Content-Type': 'application/json',
//       },
//       body: JSON.stringify(searchParams),
//     })
//     .then(response => {
//       if (!response.ok) {
//         throw new Error('Network response was not ok');
//       }
//       return response.json();
//     })
//     .then(data => {
//       console.log('Success:', data);
//     })
//     .catch((error) => {
//       console.error('Error:', error);
//     });
//   };

//   return (
//     <div className="container">
//       <Card className="search-card">
//         <Card.Header as="h5" className="header">اختيار مقدم الرعاية</Card.Header>
//         <Card.Body>
//           <Form onSubmit={handleSubmit}>
//             {/* حقول الفلترة */}
//             <Form.Group controlId="serviceControl">
//               <Form.Label>نوع الخدمة</Form.Label>
//               <Form.Control as="select" name="service" value={searchParams.service} onChange={handleInputChange} className='select-style'>
//                 <option value="" disabled>اختر...</option>
//                 {services.map(service => (
//                   <option key={service.id} value={service.name}>{service.name}</option>
//                 ))}
//               </Form.Control>
//             </Form.Group>

//             <Form.Group controlId="genderControl">
//               <Form.Label>جنس مقدم الرعاية</Form.Label>
//               <Form.Control as="select" name="gender" value={searchParams.gender} onChange={handleInputChange} className='select-style'>
//                 <option value="" disabled >اختر...</option>
//                 <option value="male">ذكر</option>
//                 <option value="female">أنثى</option>
//               </Form.Control>
//             </Form.Group>

//             <Form.Group controlId="ageControl">
//               <Form.Label>العمر</Form.Label>
//               <Form.Control type="number" name="age" min="20" value={searchParams.age} onChange={handleInputChange} />
//             </Form.Group>

//             <Form.Group controlId="physicalStrengthControl">
//               <Form.Label>القوة البدنية</Form.Label>
//               <Form.Control as="select" name="physicalStrength" value={searchParams.physicalStrength} onChange={handleInputChange} className='select-style'>
//                 <option value="" disabled>اختر...</option>
//                 <option value="basic">أساسية</option>
//                 <option value="advanced">متقدمة</option>
//                 <option value="professional">احترافية</option>
//               </Form.Control>
//             </Form.Group>

//             <Form.Group controlId="experienceControl">
//               <Form.Label>عدد سنوات الخبرة</Form.Label>
//               <Form.Control type="number" name="experience" value={searchParams.experience} onChange={handleInputChange} className='select-style' />
//             </Form.Group><Form.Group controlId="skillControl">
//               <Form.Label>المهارات</Form.Label>
//               <Form.Control as="select" name="skill" value={searchParams.skill} onChange={handleInputChange}>
//                 <option value="" disabled>اختر...</option>
//                 {skills.map(skill => (
//                   <option key={skill.id} value={skill.name}>{skill.name}</option>
//                 ))}
//               </Form.Control>
//             </Form.Group>
//            <Link to='noptions'>
//             <Button type="submit" className='nbtn'>بحث</Button>
//             </Link>
//           </Form>
//         </Card.Body>
//       </Card>
//     </div>
//   );
// };

// export default Nstate;
import React, { useState, useEffect } from 'react';
import { Form, Button, Card } from 'react-bootstrap';
import './Nstate.css';
import { Link, useNavigate } from 'react-router-dom';

const Nstate = () => {
  const [searchParams, setSearchParams] = useState({
    service: '',
    gender: '',
    age: '',
    physicalStrength: '',
    experience: '',
    skill: ''
  });

  const [skills, setSkills] = useState([]);
  const [services, setServices] = useState([]);
  const navigate = useNavigate();

  useEffect(() => {
    fetch('http://127.0.0.1:8000/api/search')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        setSkills(data.skills.original.data);
        setServices(data.services.original.data);
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }, []);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setSearchParams(prevParams => ({
      ...prevParams,
      [name]: value
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // إنشاء رابط URL مع معاملات البحث
    const queryParams = new URLSearchParams(searchParams).toString();
    // استخدام navigate لتوجيه المستخدم إلى واجهة Noptions مع المعاملات
    navigate(`/noptions?${queryParams}`);
  };
  return (
    <div className="container">
      <Card className="search-card">
        <Card.Header as="h5" className="header">اختيار مقدم الرعاية</Card.Header>
        <Card.Body>
          <Form onSubmit={handleSubmit}>
            {/* حقول الفلترة */}
            <Form.Group controlId="serviceControl">
              <Form.Label>نوع الخدمة</Form.Label>
              <Form.Control as="select" name="service" value={searchParams.service} onChange={handleInputChange} className='select-style'>
                <option value="" disabled>اختر...</option>
                {services.map(service => (
                  <option key={service.id} value={service.name}>{service.name}</option>
                ))}
              </Form.Control>
            </Form.Group>
    
            <Form.Group controlId="genderControl">
              <Form.Label>جنس مقدم الرعاية</Form.Label>
              <Form.Control as="select" name="gender" value={searchParams.gender} onChange={handleInputChange} className='select-style'>
                <option value="" disabled >اختر...</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
              </Form.Control>
            </Form.Group>

            <Form.Group controlId="ageControl">
              <Form.Label>العمر</Form.Label>
              <Form.Control type="number" name="age" min="20" value={searchParams.age} onChange={handleInputChange} />
            </Form.Group>

            <Form.Group controlId="physicalStrengthControl">
              <Form.Label>القوة البدنية</Form.Label>
              <Form.Control as="select" name="physicalStrength" value={searchParams.physicalStrength} onChange={handleInputChange} className='select-style'>
                <option value="" disabled>اختر...</option>
                <option value="basic">أساسية</option>
                <option value="advanced">متقدمة</option>
                <option value="professional">احترافية</option>
              </Form.Control>
            </Form.Group>

            <Form.Group controlId="experienceControl">
              <Form.Label>عدد سنوات الخبرة</Form.Label>
              <Form.Control type="number" name="experience" value={searchParams.experience} onChange={handleInputChange} className='select-style' />
            </Form.Group>

            <Form.Group controlId="skillControl">
              <Form.Label>المهارات</Form.Label>
              <Form.Control as="select" name="skill" value={searchParams.skill} onChange={handleInputChange}>
                <option value="" disabled>اختر...</option>
              
{skills.map(skill => (
  <option key={skill.id} value={skill.name}>{skill.name}</option>
))}
</Form.Control>
</Form.Group>

<Button type="submit" className='nbtn'>بحث</Button>

</Form>
</Card.Body>
</Card>
</div>
);
};

export default Nstate;